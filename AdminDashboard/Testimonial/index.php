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
    $id_testimoni = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $judul_testimoni = mysqli_real_escape_string($conn, $_POST['input_Judul']);
    $isi_testimoni = mysqli_real_escape_string($conn, $_POST['input_Isi']);
    $nama = mysqli_real_escape_string($conn, $_POST['input_Nama']);
    $kota_negara = mysqli_real_escape_string($conn, $_POST['input_KotaNegara']);
    $namafoto = $_FILES['foto_testimoni']['name'];
    $file_tmp = $_FILES['foto_testimoni']['tmp_name'];

    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    $result = mysqli_query($conn, "INSERT INTO testimoni(id_testimoni, judul_testimoni, isi_testimoni, nama, kota_negara, foto_testimoni) VALUES('$id_testimoni','$judul_testimoni','$isi_testimoni','$nama','$kota_negara','$namafoto')");

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
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
                    <form method="POST" class="mt-5" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="id_testimoni" class="form-label">ID Testimoni</label>
                            <input type="text" class="form-control" name="input_ID" id="id_testimoni" required>
                        </div>
                        <div class="mb-3">
                            <label for="judul_testimoni" class="form-label">Judul Testimoni</label>
                            <input type="text" class="form-control" name="input_Judul" id="judul_testimoni" required>
                        </div>
                        <div class="mb-3">
                            <label for="isi_testimoni" class="form-label">Isi Testimoni</label>
                            <textarea class="form-control" name="input_Isi" id="isi_testimoni" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="input_Nama" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kota_negara" class="form-label">Kota/Negara</label>
                            <input type="text" class="form-control" name="input_KotaNegara" id="kota_negara" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Foto Testimoni</label>
                            <input type="file" class="form-control" name="foto_testimoni" id="file" required>
                            <p class="help-block">Unggah Foto Testimoni</p>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

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