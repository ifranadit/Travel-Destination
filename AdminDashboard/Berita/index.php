<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}

// Include database connection file
include("../includes/config.php");

// Cek jika form disubmit
if (isset($_POST['submit'])) {
    $berita_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $berita_Judul = mysqli_real_escape_string($conn, $_POST['input_Judul']);
    $berita_Isi = mysqli_real_escape_string($conn, $_POST['input_Isi']);
    $berita_Sumber = mysqli_real_escape_string($conn, $_POST['input_Sumber']);
    $kategoriKode = mysqli_real_escape_string($conn, $_POST['kategoriID']);
    $namafoto = $_FILES['fotoberita']['name'];
    $file_tmp = $_FILES['fotoberita']['tmp_name'];

    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    $result = mysqli_query($conn, "INSERT INTO berita(berita_id, berita_judul, berita_isi, berita_sumber, berita_foto, kategori_id) VALUES('$berita_ID','$berita_Judul','$berita_Isi','$berita_Sumber','$namafoto','$kategoriKode')");

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan berita: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch all categories data from the database
$datakategori = mysqli_query($conn, "SELECT * FROM kategori");
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
                    <form method="POST" class="mt-3" enctype="multipart/form-data">
                        <!-- Form inputs for adding new data -->
                        <div class="mb-3">
                            <label for="berita_ID" class="form-label">Berita ID</label>
                            <input type="text" class="form-control" name="input_ID" id="berita_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="berita_Judul" class="form-label">Judul Berita</label>
                            <input type="text" class="form-control" name="input_Judul" id="berita_Judul" required>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="input_Isi" id="berita_Isi" required></textarea>
                            <label for="berita_Isi">Isi berita</label>
                        </div>
                        <div class="mb-3">
                            <label for="berita_Sumber" class="form-label">Sumber berita</label>
                            <input type="text" class="form-control" name="input_Sumber" id="berita_Sumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategoriID" class="form-label">Kode Kategori</label>
                            <select class="form-control" name="kategoriID" id="kategoriID" required>
                                <option value="">Pilih Kode Kategori</option>
                                <?php while ($row = mysqli_fetch_array($datakategori)) { ?>
                                    <option value="<?php echo $row["kategori_id"]; ?>">
                                        <?php echo $row["kategori_id"] . " - " . $row["kategori_NAMA"]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotoberita" id="file" required>
                            <p class="help-block">Unggah Foto Berita</p>
                        </div>
                        <button type="submit" class="btn btn-success" name="submit">Tambah Berita</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>
                    <?php
                    // Fetch berita with category name
                    $searchQuery = "SELECT berita.*, kategori.kategori_NAMA FROM berita JOIN kategori ON berita.kategori_id = kategori.kategori_id";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE berita.berita_judul LIKE '%$cari%' OR berita.berita_isi LIKE '%$cari%' OR berita.berita_sumber LIKE '%$cari%'";
                    }
                    $query = mysqli_query($conn, $searchQuery . " ORDER BY berita.berita_id ASC");
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
                                <th scope="col">Berita ID</th>
                                <th scope="col">Judul Berita</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Sumber berita</th>
                                <th scope="col">Kode Kategori</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Foto</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                while ($berita_Data = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $berita_Data['berita_id'] . "</td>";
                                    echo "<td>" . $berita_Data['berita_judul'] . "</td>";
                                    echo "<td>" . $berita_Data['berita_isi'] . "</td>";
                                    echo "<td>" . $berita_Data['berita_sumber'] . "</td>";
                                    echo "<td>" . $berita_Data['kategori_id'] . "</td>";
                                    echo "<td>" . $berita_Data['kategori_NAMA'] . "</td>";
                                    echo "<td><img src='images/" . $berita_Data['berita_foto'] . "' width='100' alt='Foto Berita'></td>";
                                    echo "<td>
                                        <a href='edit.php?berita_ID=" . $berita_Data['berita_id'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                                        </a> 
                                        </td>";

                                    echo "<td>
                                        <a href='delete.php?id=" . $berita_Data['berita_id'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                                        </a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Tidak ada data berita</td></tr>";
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