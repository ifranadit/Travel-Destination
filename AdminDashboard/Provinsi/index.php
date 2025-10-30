<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}
// Create database connection using config file
include("../includes/config.php");

if (isset($_POST['submit'])) {
    $provinsi_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $provinsi_Nama = mysqli_real_escape_string($conn, $_POST['input_Pronama']);
    $namafoto = $_FILES['fotoprovinsi']['name'];
    $file_tmp = $_FILES['fotoprovinsi']['tmp_name'];

    // Pindahkan file yang diupload ke folder images
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    // Insert data ke tabel provinsi
    $query = "INSERT INTO provinsi (provinsi_id, provinsi_nama, provinsi_foto) 
            VALUES ('$provinsi_ID', '$provinsi_Nama', '$namafoto')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan provinsi: " . mysqli_error($conn) . "</div>";
    }
}

// Query untuk menampilkan semua data provinsi atau data hasil pencarian
$searchQuery = "SELECT * FROM provinsi";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $searchQuery .= " WHERE provinsi_nama LIKE '%$cari%'";
}
$searchQuery .= " ORDER BY provinsi_id ASC";
$result = mysqli_query($conn, $searchQuery);
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
                    <form action="" method="POST" class="mt-5" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="provinsi_ID" class="form-label">Provinsi ID</label>
                            <input type="text" class="form-control" name="input_ID" id="provinsi_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="provinsi_Nama" class="form-label">Provinsi Nama</label>
                            <input type="text" class="form-control" name="input_Pronama" id="provinsi_Nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotoprovinsi" id="file" required>
                            <p class="help-block">Unggah Foto Provinsi</p>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <hr>
                    <form action="index.php" method="get">
                        <div class="col-6 d-flex">
                            <input type="text" class="form-control" name="cari" value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                            <Button type="submit" class="btn btn-primary ms-3">Search</Button>
                        </div>
                    </form>
                    <br>

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