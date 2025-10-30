<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}
// include database connection file
include("../includes/config.php");

// Set directory to store images
$target_dir = "images/";

if (isset($_POST['update'])) {
    $kabupaten_ID = $_POST['input_id'];
    $kabupaten_Nama = $_POST['input_nama'];
    $kabupaten_alamat = $_POST['input_alamat'];
    $provinsiID = $_POST['provinsiID'];
    $namafoto = $_FILES['fotokabupaten']['name'];
    $file_tmp = $_FILES['fotokabupaten']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if exists
            $result = mysqli_query($conn, "SELECT kabupaten_foto FROM kabupaten WHERE kabupaten_id='$kabupaten_ID'");
            $row = mysqli_fetch_assoc($result);
            if ($row['kabupaten_foto'] && file_exists($target_dir . $row['kabupaten_foto'])) {
                unlink($target_dir . $row['kabupaten_foto']);
            }

            // Update database with the new image name
            $result = mysqli_query($conn, "UPDATE kabupaten SET kabupaten_nama='$kabupaten_Nama', kabupaten_alamat='$kabupaten_alamat', provinsi_id='$provinsiID', kabupaten_foto='$namafoto' WHERE kabupaten_id='$kabupaten_ID'");
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        // If no new image, just update other fields
        $result = mysqli_query($conn, "UPDATE kabupaten SET kabupaten_nama='$kabupaten_Nama', kabupaten_alamat='$kabupaten_alamat', provinsi_id='$provinsiID' WHERE kabupaten_id='$kabupaten_ID'");
    }

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected user data based on id
if (isset($_GET['kabupaten_ID'])) {
    $kabupaten_ID = $_GET['kabupaten_ID'];

    // Fetch user data based on id
    $result = mysqli_query($conn, "SELECT * FROM kabupaten WHERE kabupaten_id='$kabupaten_ID'");

    if (mysqli_num_rows($result) > 0) {
        $kabupaten_Data = mysqli_fetch_array($result);
        $kabupaten_Nama = $kabupaten_Data['kabupaten_nama'];
        $kabupaten_alamat = $kabupaten_Data['kabupaten_alamat'];
        $provinsiID = $kabupaten_Data['provinsi_id'];
        $kabupaten_Foto = $kabupaten_Data['kabupaten_foto'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Fetch all province data for dropdown
$dataprovinsi = mysqli_query($conn, "SELECT * FROM provinsi");
?>

<!DOCTYPE html>
<html lang="en">

<?php include('../include/head.php'); ?>

<body class="sb-nav-fixed">
    <?php include('../include/menunav.php'); ?>

    <div id="layoutSidenav">
        <?php include('../include/menu.php'); ?>

        <div id="layoutSidenav_content">
            <main>

                <div class="container-fluid px-4">
                    <form action="edit.php" method="POST" enctype="multipart/form-data" class="mt-5">
                        <div class="mb-3">
                            <label for="kabupaten_ID" class="form-label">Kabupaten ID</label>
                            <input type="text" class="form-control" name="input_id" value="<?php echo $kabupaten_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kabupaten_Nama" class="form-label">Nama Kabupaten</label>
                            <input type="text" class="form-control" name="input_nama" value="<?php echo $kabupaten_Nama; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="input_alamat" class="form-label">Alamat Kabupaten</label>
                            <textarea class="form-control" name="input_alamat"><?php echo $kabupaten_alamat; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="provinsiID" class="col-form-label">Kode Provinsi</label>
                            <select class="form-control select2" name="provinsiID" id="provinsiID" required>
                                <option value="">Pilih Kode Provinsi</option>
                                <?php while ($row = mysqli_fetch_array($dataprovinsi)) { ?>
                                    <option value="<?php echo $row["provinsi_id"] ?>"
                                        <?php echo ($row["provinsi_id"] == $provinsiID) ? 'selected' : ''; ?>>
                                        <?php echo $row["provinsi_id"] . " - " . $row["provinsi_nama"]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotokabupaten" id="file">
                            <p class="help-block">Unggah Foto Kabupaten</p>
                            <?php if (!empty($kabupaten_Foto)) { ?>
                                <img src="<?php echo $target_dir . $kabupaten_Foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>


                    <?php
                    // Fetch kabupaten data with provinsi name
                    $searchQuery = "SELECT kabupaten.*, provinsi.provinsi_nama 
                    FROM kabupaten 
                    JOIN provinsi ON kabupaten.provinsi_id = provinsi.provinsi_id";

                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE kabupaten.kabupaten_nama LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY kabupaten.kabupaten_id ASC";
                    $query = mysqli_query($conn, $searchQuery);
                    ?>
                    <br>
                    <table class="table table-bordered table-success table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Kabupaten ID</th>
                                <th scope="col">Nama Kabupaten</th>
                                <th scope="col">Alamat Kabupaten</th>
                                <th scope="col">Kode Provinsi</th>
                                <th scope="col">Nama Provinsi</th>
                                <th scope="col">Foto</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                while ($kabupaten_data = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $kabupaten_data['kabupaten_id'] . "</td>";
                                    echo "<td>" . $kabupaten_data['kabupaten_nama'] . "</td>";
                                    echo "<td>" . $kabupaten_data['kabupaten_alamat'] . "</td>";
                                    echo "<td>" . $kabupaten_data['provinsi_id'] . "</td>";
                                    echo "<td>" . $kabupaten_data['provinsi_nama'] . "</td>";
                                    echo "<td><img src='images/" . $kabupaten_data['kabupaten_foto'] . "' width='100' alt='Foto kabupaten'></td>";
                                    echo "<td>
                            <a href='edit.php?kabupaten_ID=" . $kabupaten_data['kabupaten_id'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                            </a> 
                            </td>";

                                    echo "<td>
                            <a href='delete.php?id=" . $kabupaten_data['kabupaten_id'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                            </a>
                        </td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data kabupaten</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php include('../include/footer.php'); ?>
        </div>
    </div>
    <?php include('../include/jsscript.php'); ?>
</body>

</html>