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
    $kecamatan_ID = $_POST['input_ID'];
    $kecamatan_Nama = $_POST['input_Kecnama'];
    $kabupatenKode = $_POST['kabupatenKode']; // corrected the variable name
    $namafoto = $_FILES['fotokecamatan']['name'];
    $file_tmp = $_FILES['fotokecamatan']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT kecamatan_foto FROM kecamatan WHERE kecamatan_id='$kecamatan_ID'");
            $row = mysqli_fetch_assoc($result);
            if ($row['kecamatan_foto'] && file_exists($target_dir . $row['kecamatan_foto'])) {
                unlink($target_dir . $row['kecamatan_foto']);
            }

            // Update database with the new image name
            $result = mysqli_query($conn, "UPDATE kecamatan SET kecamatan_nama='$kecamatan_Nama', kabupaten_id='$kabupatenKode', kecamatan_foto='$namafoto' WHERE kecamatan_id='$kecamatan_ID'");
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        // If no new image, just update other fields
        $result = mysqli_query($conn, "UPDATE kecamatan SET kecamatan_nama='$kecamatan_Nama', kabupaten_id='$kabupatenKode' WHERE kecamatan_id='$kecamatan_ID'");
    }

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected user data based on id
if (isset($_GET['kecamatan_ID'])) {
    $kecamatan_ID = $_GET['kecamatan_ID'];

    // Fetch kecamatan data joined with kabupaten to display kabupaten name
    $result = mysqli_query($conn, "SELECT kecamatan.*, kabupaten.kabupaten_nama FROM kecamatan 
    LEFT JOIN kabupaten ON kecamatan.kabupaten_id = kabupaten.kabupaten_id 
    WHERE kecamatan.kecamatan_id='$kecamatan_ID'");

    if (mysqli_num_rows($result) > 0) {
        $kecamatan_Data = mysqli_fetch_array($result);
        $kecamatan_Nama = $kecamatan_Data['kecamatan_nama'];
        $kabupatenKode = $kecamatan_Data['kabupaten_id'];
        $kecamatan_foto = $kecamatan_Data['kecamatan_foto'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Fetch all kabupaten data for dropdown
$datakecamatan = mysqli_query($conn, "SELECT * FROM kabupaten");
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
                            <label for="kecamatan_ID" class="form-label">Kecamatan ID</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $kecamatan_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan_Nama" class="form-label">Nama Kecamatan</label>
                            <input type="text" class="form-control" name="input_Kecnama" value="<?php echo $kecamatan_Nama; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="kabupatenKode" class="col-form-label">Kode Kabupaten</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kabupatenKode" id="kabupatenKode" required>
                                    <option value="">Pilih Kode Kabupaten</option>
                                    <?php while ($row = mysqli_fetch_array($datakecamatan)) { ?>
                                        <option value="<?php echo $row["kabupaten_id"] ?>"
                                            <?php echo ($row["kabupaten_id"] == $kabupatenKode) ? 'selected' : ''; ?>>
                                            <?php echo $row["kabupaten_id"] . " - " . $row["kabupaten_nama"]; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotokecamatan" id="file">
                            <p class="help-block">Unggah Foto Kecamatan</p>
                            <?php if (!empty($kecamatan_foto)) { ?>
                                <img src="<?php echo $target_dir . $kecamatan_foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>
                    <?php
                    // Fetch kecamatan with kabupaten name
                    $searchQuery = "SELECT kecamatan.*, kabupaten.kabupaten_Nama FROM kecamatan JOIN kabupaten ON kecamatan.kabupaten_id = kabupaten.kabupaten_id";

                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE kecamatan.kecamatan_nama LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY kecamatan.kecamatan_id ASC";
                    $query = mysqli_query($conn, $searchQuery);

                    ?>
                    <table class="table table-bordered table-success table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Kecamatan ID</th>
                                <th scope="col">Kecamatan Nama</th>
                                <th scope="col">Kode Kabupaten</th>
                                <th scope="col">Nama Kabupaten</th>
                                <th scope="col">Foto</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                while ($kecamatan_Data = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $kecamatan_Data['kecamatan_id'] . "</td>";
                                    echo "<td>" . $kecamatan_Data['kecamatan_nama'] . "</td>";
                                    echo "<td>" . $kecamatan_Data['kabupaten_id'] . "</td>";
                                    echo "<td>" . $kecamatan_Data['kabupaten_Nama'] . "</td>";
                                    echo "<td><img src='images/" . $kecamatan_Data['kecamatan_foto'] . "' width='100' alt='Foto Kecamatan'></td>";
                                    echo "<td>
                            <a href='edit.php?kecamatan_ID=" . $kecamatan_Data['kecamatan_id'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                            </a> 

                            </td>";

                                    echo "<td>
                            <a href='delete.php?id=" . $kecamatan_Data['kecamatan_id'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                            </a>
                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data Kecamatan</td></tr>";
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