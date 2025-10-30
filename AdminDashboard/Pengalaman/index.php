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
    $pengalaman_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $pengalaman_Judul = mysqli_real_escape_string($conn, $_POST['input_PengJudul']);
    $pengalaman_SubJudul = mysqli_real_escape_string($conn, $_POST['input_PengSubJudul']);
    $pengalaman_Keterangan = mysqli_real_escape_string($conn, $_POST['input_PengKeterangan']);
    $pengalaman_LinkVideo = mysqli_real_escape_string($conn, $_POST['input_PengLinkVideo']);
    $namafoto = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];

    // Pindahkan file yang diupload ke folder images
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    // Insert data ke tabel pengalaman
    $query = "INSERT INTO pengalaman (id_pengalaman, judul, sub_judul, keterangan, link_video, foto) 
            VALUES ('$pengalaman_ID', '$pengalaman_Judul', '$pengalaman_SubJudul', '$pengalaman_Keterangan', '$pengalaman_LinkVideo', '$namafoto')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan Pengalaman: " . mysqli_error($conn) . "</div>";
    }
}

// Query untuk menampilkan semua data pengalaman atau data hasil pencarian
$searchQuery = "SELECT * FROM pengalaman";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $searchQuery .= " WHERE judul LIKE '%$cari%'";
}
$searchQuery .= " ORDER BY id_pengalaman ASC";
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
                            <label for="pengalaman_ID" class="form-label">Pengalaman ID</label>
                            <input type="text" class="form-control" name="input_ID" id="pengalaman_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="pengalaman_Judul" class="form-label">Pengalaman Judul</label>
                            <input type="text" class="form-control" name="input_PengJudul" id="pengalaman_Judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="pengalaman_SubJudul" class="form-label">Pengalaman Sub judul</label>
                            <input type="text" class="form-control" name="input_PengSubJudul" id="pengalaman_SubJudul" required>
                        </div>
                        <div class="mb-3">
                            <label for="pengalaman_keterangan" class="form-label">Keterangan Pengalaman</label>
                            <textarea class="form-control" name="input_PengKeterangan" id="pengalaman_keterangan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="pengalaman_LinkVideo" class="form-label">Pengalaman Link Video</label>
                            <input type="text" class="form-control" name="input_PengLinkVideo" id="pengalaman_LinkVideo" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="foto" id="file" required>
                            <p class="help-block">Unggah Foto Pengalaman</p>
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
                                <th scope="col">Pengalaman ID</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Sub Judul</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Link Video</th>
                                <th scope="col">Foto Pengalaman</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($pengalaman_data = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $pengalaman_data['id_pengalaman'] . "</td>";
                                    echo "<td>" . $pengalaman_data['judul'] . "</td>";
                                    echo "<td>" . $pengalaman_data['sub_judul'] . "</td>";
                                    echo "<td>" . $pengalaman_data['keterangan'] . "</td>";
                                    echo "<td>" . $pengalaman_data['link_video'] . "</td>";
                                    echo "<td><img src='images/" . $pengalaman_data['foto'] . "' width='100' alt='Foto Pengalaman'></td>";
                                    echo "<td>
                                <a href='edit.php?id_pengalaman=" . $pengalaman_data['id_pengalaman'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                                </a>
                            </td>";
                                    echo "<td>
                                <a href='delete.php?id=" . $pengalaman_data['id_pengalaman'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                                </a>
                            </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data pengalaman</td></tr>";
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