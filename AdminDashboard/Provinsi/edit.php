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
    $provinsi_ID = $_POST['input_ID'];
    $provinsi_Nama = $_POST['input_Pronama'];
    $namafoto = $_FILES['fotoprovinsi']['name'];
    $file_tmp = $_FILES['fotoprovinsi']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT provinsi_foto FROM provinsi WHERE provinsi_id='$provinsi_ID'");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row['provinsi_foto'] && file_exists($target_dir . $row['provinsi_foto'])) {
                    unlink($target_dir . $row['provinsi_foto']);
                }
            }

            // Update database with the new image name
            $update_result = mysqli_query($conn, "UPDATE provinsi SET provinsi_nama='$provinsi_Nama', provinsi_foto='$namafoto' WHERE provinsi_id='$provinsi_ID'");
            if (!$update_result) {
                echo "Failed to update data.";
                exit;
            }
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        // If no new image, just update other fields
        $update_result = mysqli_query($conn, "UPDATE provinsi SET provinsi_nama='$provinsi_Nama' WHERE provinsi_id='$provinsi_ID'");
        if (!$update_result) {
            echo "Failed to update data.";
            exit;
        }
    }

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected user data based on id
if (isset($_GET['provinsi_ID'])) {
    $provinsi_ID = $_GET['provinsi_ID'];
    $result = mysqli_query($conn, "SELECT * FROM provinsi WHERE provinsi_id='$provinsi_ID'");

    if ($result && mysqli_num_rows($result) > 0) {
        $provinsi_Data = mysqli_fetch_assoc($result);
        $provinsi_Nama = $provinsi_Data['provinsi_nama'];
        $provinsi_Foto = $provinsi_Data['provinsi_foto'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Fetch all kabupaten data for dropdown
$datakecamatan = mysqli_query($conn, "SELECT * FROM provinsi");
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
                            <label for="provinsi_ID" class="form-label">Provinsi ID</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $provinsi_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="provinsi_Nama" class="form-label">Nama Provinsi</label>
                            <input type="text" class="form-control" name="input_Pronama" value="<?php echo $provinsi_Nama; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotoprovinsi" id="file">
                            <p class="help-block">Unggah Foto Provinsi</p>
                            <?php if (!empty($provinsi_Foto)) { ?>
                                <img src="<?php echo $target_dir . $provinsi_Foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>
                    <?php
                    $searchQuery = "SELECT * FROM provinsi";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE provinsi_nama LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY provinsi_id ASC";
                    $result = mysqli_query($conn, $searchQuery);
                    ?>

                    <table class="table table-bordered table-success table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Provinsi ID</th>
                                <th scope="col">Nama Provinsi</th>
                                <th scope="col">Foto Provinsi</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($provinsi_Data = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $provinsi_Data['provinsi_id'] . "</td>";
                                    echo "<td>" . $provinsi_Data['provinsi_nama'] . "</td>";
                                    echo "<td><img src='images/" . $provinsi_Data['provinsi_foto'] . "' width='100' alt='Foto Provinsi'></td>";
                                    echo "<td>
                                        <a href='edit.php?provinsi_ID=" . $provinsi_Data['provinsi_id'] . "'>
                                                        <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                                        </a> 
                                    </td>";
                                    echo "<td>
                                        <a href='delete.php?id=" . $provinsi_Data['provinsi_id'] . "'>
                                        <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                                        </a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Tidak ada data provinsi</td></tr>";
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