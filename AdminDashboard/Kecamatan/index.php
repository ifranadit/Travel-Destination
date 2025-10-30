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
    $kecamatan_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $kecamatan_Nama = mysqli_real_escape_string($conn, $_POST['input_Kecnama']);
    $kabupatenKode = mysqli_real_escape_string($conn, $_POST['kabupatenID']);
    $namafoto = $_FILES['fotokecamatan']['name'];
    $file_tmp = $_FILES['fotokecamatan']['tmp_name'];

    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    $result = mysqli_query($conn, "INSERT INTO kecamatan(kecamatan_id, kecamatan_nama, kecamatan_foto, kabupaten_id) VALUES('$kecamatan_ID','$kecamatan_Nama','$namafoto','$kabupatenKode')");

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch all kabupaten data from the database
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
                            <label for="kecamatan_ID" class="form-label">Kecamatan ID</label>
                            <input type="text" class="form-control" name="input_ID" id="kecamatan_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan_Nama" class="form-label">Nama Kecamatan</label>
                            <input type="text" class="form-control" name="input_Kecnama" id="kecamatan_Nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kabupatenID" class="col-form-label">Kode Kabupaten</label>
                            <select class="form-control" name="kabupatenID" id="kabupatenID" required>
                                <option value="">Pilih Kode Kabupaten</option>
                                <?php while ($row = mysqli_fetch_array($datakabupaten)) { ?>
                                    <option value="<?php echo $row["kabupaten_id"] ?>">
                                        <?php echo $row["kabupaten_id"] ?> - <?php echo $row["kabupaten_nama"] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotokecamatan" id="file" required>
                            <p class="help-block">Unggah Foto Kecamatan</p>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>