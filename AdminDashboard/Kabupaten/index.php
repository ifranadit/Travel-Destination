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
    $kabupaten_id = mysqli_real_escape_string($conn, $_POST['input_id']);
    $kabupaten_nama = mysqli_real_escape_string($conn, $_POST['input_nama']);
    $kabupaten_alamat = mysqli_real_escape_string($conn, $_POST['input_alamat']);
    $provinsiID = mysqli_real_escape_string($conn, $_POST['provinsiID']);
    $namafoto = $_FILES['fotokabupaten']['name'];
    $file_tmp = $_FILES['fotokabupaten']['tmp_name'];

    // Move the uploaded file to the images directory
    if (move_uploaded_file($file_tmp, 'images/' . $namafoto)) {
        // Insert data into kabupaten table
        $query = "INSERT INTO kabupaten (kabupaten_id, kabupaten_nama, kabupaten_alamat, provinsi_id, kabupaten_foto) 
                VALUES ('$kabupaten_id', '$kabupaten_nama', '$kabupaten_alamat', '$provinsiID', '$namafoto')";

        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan kabupaten: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Gagal mengunggah foto kabupaten.</div>";
    }
}

// Fetch all provinsi data from the database
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
                    <form method="POST" class="mt-5" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="kabupaten_id" class="form-label">Kabupaten ID</label>
                            <input type="text" class="form-control" name="input_id" id="kabupaten_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="kabupaten_nama" class="form-label">Nama Kabupaten</label>
                            <input type="text" class="form-control" name="input_nama" id="kabupaten_nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="provinsiID" class="col-form-label">Kode Provinsi</label>
                            <select class="form-control select2" name="provinsiID" id="provinsiID" required>
                                <option value="">Pilih Kode Provinsi</option>
                                <?php while ($row = mysqli_fetch_array($dataprovinsi)) { ?>
                                    <option value="<?php echo $row["provinsi_id"] ?>">
                                        <?php echo $row["provinsi_id"] ?> - <?php echo $row["provinsi_nama"] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="input_alamat" id="kabupaten_alamat" required></textarea>
                            <label for="kabupaten_alamat">Alamat Kabupaten</label>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotokabupaten" id="file" required>
                            <p class="help-block">Unggah Foto Kabupaten</p>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
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

    <!-- Select2 Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#provinsiID').select2();
        });
    </script>

</body>

</html>