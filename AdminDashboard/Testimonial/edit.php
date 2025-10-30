<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}
// Include database connection file
include("../includes/config.php");

// Set directory to store images
$target_dir = "images/";

if (isset($_POST['update'])) {
    $id_testimoni = $_POST['input_ID'];
    $judul_testimoni = $_POST['input_Judul'];
    $isi_testimoni = $_POST['input_IsiTestimoni'];
    $nama = $_POST['input_Nama'];
    $kota_negara = $_POST['input_KotaNegara'];
    $namafoto = $_FILES['foto_testimoni']['name'];
    $file_tmp = $_FILES['foto_testimoni']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT foto_testimoni FROM testimoni WHERE id_testimoni='$id_testimoni'");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row['foto_testimoni'] && file_exists($target_dir . $row['foto_testimoni'])) {
                    unlink($target_dir . $row['foto_testimoni']);
                }
            }

            // Update database with the new image name
            $update_result = mysqli_query($conn, "UPDATE testimoni SET judul_testimoni='$judul_testimoni', isi_testimoni='$isi_testimoni', nama='$nama', kota_negara='$kota_negara', foto_testimoni='$namafoto' WHERE id_testimoni='$id_testimoni'");
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
        $update_result = mysqli_query($conn, "UPDATE testimoni SET judul_testimoni='$judul_testimoni', isi_testimoni='$isi_testimoni', nama='$nama', kota_negara='$kota_negara' WHERE id_testimoni='$id_testimoni'");
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
if (isset($_GET['id_testimoni'])) {
    $id_testimoni = $_GET['id_testimoni'];
    $result = mysqli_query($conn, "SELECT * FROM testimoni WHERE id_testimoni='$id_testimoni'");

    if ($result && mysqli_num_rows($result) > 0) {
        $testimoni_Data = mysqli_fetch_assoc($result);
        $judul_testimoni = $testimoni_Data['judul_testimoni'];
        $isi_testimoni = $testimoni_Data['isi_testimoni'];
        $nama = $testimoni_Data['nama'];
        $kota_negara = $testimoni_Data['kota_negara'];
        $foto_testimoni = $testimoni_Data['foto_testimoni'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}
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
                            <label for="id_testimoni" class="form-label">ID Testimoni</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $id_testimoni; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="judul_testimoni" class="form-label">Judul Testimoni</label>
                            <input type="text" class="form-control" name="input_Judul" value="<?php echo $judul_testimoni; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="isi_testimoni" class="form-label">Isi Testimoni</label>
                            <textarea class="form-control" name="input_IsiTestimoni" required><?php echo $isi_testimoni; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="input_Nama" value="<?php echo $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="kota_negara" class="form-label">Kota/Negara</label>
                            <input type="text" class="form-control" name="input_KotaNegara" value="<?php echo $kota_negara; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto_testimoni" class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto_testimoni" id="foto_testimoni">
                            <p class="help-block">Unggah Foto Testimoni</p>
                            <?php if (!empty($foto_testimoni)) { ?>
                                <img src="<?php echo $target_dir . $foto_testimoni; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>

                    <?php
                    // Fetch testimoni data
                    $searchQuery = "SELECT * FROM testimoni";

                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE judul_testimoni LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY id_testimoni ASC";
                    $query = mysqli_query($conn, $searchQuery);
                    ?>

                    <table class="table table-bordered table-success table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID Testimoni</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Isi</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Kota/Negara</th>
                                <th scope="col">Foto</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                while ($testimoni = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $testimoni['id_testimoni'] . "</td>";
                                    echo "<td>" . $testimoni['judul_testimoni'] . "</td>";
                                    echo "<td>" . $testimoni['isi_testimoni'] . "</td>";
                                    echo "<td>" . $testimoni['nama'] . "</td>";
                                    echo "<td>" . $testimoni['kota_negara'] . "</td>";
                                    echo "<td><img src='images/" . $testimoni['foto_testimoni'] . "' width='100' alt='Foto Testimoni'></td>";
                                    echo "<td>
                                            <a href='edit.php?id_testimoni=" . $testimoni['id_testimoni'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                                            </a>
                                        </td>";

                                    echo "<td>
                                            <a href='delete.php?id=" . $testimoni['id_testimoni'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button></a>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Tidak ada data Testimoni</td></tr>";
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