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
    $destinasi_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $destinasi_Nama = mysqli_real_escape_string($conn, $_POST['input_Desnama']);
    $destinasi_Alamat = mysqli_real_escape_string($conn, $_POST['input_Desalamat']);
    $destinasi_Keterangan = mysqli_real_escape_string($conn, $_POST['input_Desket']);
    $kecamatan_ID = mysqli_real_escape_string($conn, $_POST['kecamatanID']);
    $kategori_ID = mysqli_real_escape_string($conn, $_POST['kategoriID']);
    $kabupaten_ID = mysqli_real_escape_string($conn, $_POST['kabupatenID']);

    // Validasi file gambar
    $allowed_types = array("image/jpeg", "image/png", "image/gif");
    if (in_array($_FILES['fotodestinasi']['type'], $allowed_types)) {
        $namafoto = $_FILES['fotodestinasi']['name'];
        $file_tmp = $_FILES['fotodestinasi']['tmp_name'];

        move_uploaded_file($file_tmp, 'images/' . $namafoto);

        $result = mysqli_query($conn, "INSERT INTO destinasi(destinasi_id, destinasi_nama, destinasi_alamat, destinasi_keterangan, fotodestinasi, kecamatan_id, kategori_id, kabupaten_id) VALUES('$destinasi_ID','$destinasi_Nama','$destinasi_Alamat','$destinasi_Keterangan','$namafoto','$kecamatan_ID', '$kategori_ID', '$kabupaten_ID')");

        if ($result) {
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>File yang diunggah harus berupa gambar (JPEG, PNG, atau GIF)</div>";
    }
}

// Fetch all kecamatan data from the database
$datakecamatan = mysqli_query($conn, "SELECT * FROM kecamatan");
$datakategori = mysqli_query($conn, "SELECT * FROM kategori");
$datakabupaten = mysqli_query($conn, "SELECT * FROM kabupaten");
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
                            <label for="destinasi_ID" class="form-label">Destinasi ID</label>
                            <input type="text" class="form-control" name="input_ID" id="destinasi_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="destinasi_Nama" class="form-label">Nama Destinasi</label>
                            <input type="text" class="form-control" name="input_Desnama" id="destinasi_Nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="destinasi_Alamat" class="form-label">Alamat Destinasi</label>
                            <input type="text" class="form-control" name="input_Desalamat" id="destinasi_Alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="destinasi_Keterangan" class="form-label">Keterangan Destinasi</label>
                            <input type="text" class="form-control" name="input_Desket" id="destinasi_Keterangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatanID" class="col-form-label">Kode Kecamatan</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kecamatanID" id="kecamatanID" required>
                                    <option value="">Pilih Kode Kecamatan</option>
                                    <?php while ($row = mysqli_fetch_array($datakecamatan)) { ?>
                                        <option value="<?php echo $row["kecamatan_id"] ?>">
                                            <?php echo $row["kecamatan_id"] ?> - <?php echo $row["kecamatan_nama"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="kategoriID" class="col-form-label">Kode Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kategoriID" id="kategoriID" required>
                                    <option value="">Pilih Kode Kategori</option>
                                    <?php while ($row = mysqli_fetch_array($datakategori)) { ?>
                                        <option value="<?php echo $row["kategori_id"] ?>">
                                            <?php echo $row["kategori_id"] ?> - <?php echo $row["kategori_NAMA"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="kabupatenID" class="col-form-label">Kode Kabupaten</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kabupatenID" id="kabupatenID" required>
                                    <option value="">Pilih Kode Kabupaten</option>
                                    <?php while ($row = mysqli_fetch_array($datakabupaten)) { ?>
                                        <option value="<?php echo $row["kabupaten_id"] ?>">
                                            <?php echo $row["kabupaten_id"] ?> - <?php echo $row["kabupaten_nama"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotodestinasi" id="file" required>
                            <p class="help-block">Unggah Foto Kecamatan</p>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <?php
                    $searchQuery = "SELECT destinasi.*, kecamatan.kecamatan_nama FROM destinasi JOIN kecamatan ON destinasi.kecamatan_id = kecamatan.kecamatan_id";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE destinasi.destinasi_nama LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY destinasi.destinasi_id ASC";
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
                                <th scope="col">Destinasi ID</th>
                                <th scope="col">Destinasi Nama</th>
                                <th scope="col">Kode Kecamatan</th>
                                <th scope="col">Kode Kabupaten</th>
                                <th scope="col">Kode Kategori</th>
                                <th scope="col">Nama Kecamatan</th>
                                <th scope="col">Foto</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                while ($destinasi_Data = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $destinasi_Data['destinasi_id'] . "</td>";
                                    echo "<td>" . $destinasi_Data['destinasi_nama'] . "</td>";
                                    echo "<td>" . $destinasi_Data['kecamatan_id'] . "</td>";
                                    echo "<td>" . $destinasi_Data['kabupaten_id'] . "</td>";
                                    echo "<td>" . $destinasi_Data['kategori_id'] . "</td>";
                                    echo "<td>" . $destinasi_Data['kecamatan_nama'] . "</td>";
                                    echo "<td><img src='images/" . $destinasi_Data['fotodestinasi'] . "' width='100' alt='Foto Kecamatan'></td>";

                                    echo "<td><a href='edit.php?destinasi_ID=" . $destinasi_Data['destinasi_id'] . "'><button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>";
                                    echo "<td><a href='delete.php?id=" . $destinasi_Data['destinasi_id'] . "'><button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data Destinasi</td></tr>";
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