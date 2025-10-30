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
    $kategori_ID = mysqli_real_escape_string($conn, $_POST['input_ID']);
    $kategori_Nama = mysqli_real_escape_string($conn, $_POST['input_Katnama']);
    $kategori_Ket = mysqli_real_escape_string($conn, $_POST['input_Katket']);

    // Insert data into 'kategori' table
    $insertQuery = "INSERT INTO kategori (kategori_id, kategori_NAMA, kategori_KET) 
                    VALUES ('$kategori_ID', '$kategori_Nama', '$kategori_Ket')";

    if (mysqli_query($conn, $insertQuery)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan kategori: " . mysqli_error($conn) . "</div>";
    }
}

// Search functionality
$searchQuery = "SELECT * FROM kategori";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $searchQuery .= " WHERE kategori_NAMA LIKE '%$cari%'";
}
$searchQuery .= " ORDER BY kategori_id ASC";
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
                    <form action="" method="POST" class="mt-5">
                        <div class="mb-3">
                            <label for="kategori_ID" class="form-label">Kategori ID</label>
                            <input type="text" class="form-control" name="input_ID" id="kategori_ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori_Nama" class="form-label">Kategori Nama</label>
                            <input type="text" class="form-control" name="input_Katnama" id="kategori_Nama" required>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="input_Katket" id="kategori_Ket" required></textarea>
                            <label for="kategori_Ket">Kategori Keterangan</label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <hr>
                    <form action="index.php" method="get">
                        <div class="col-6 d-flex">
                            <input type="text" class="form-control" name="cari" value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                            <button type="submit" class="btn btn-primary ms-3">Search</button>
                        </div>
                    </form>
                    <br>

                    <table class="table table-bordered table-success table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Kategori ID</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-center">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($kategori_Data = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $kategori_Data['kategori_id'] . "</td>";
                                    echo "<td>" . $kategori_Data['kategori_NAMA'] . "</td>";
                                    echo "<td>" . $kategori_Data['kategori_KET'] . "</td>";
                                    echo "<td>
                            <a href='edit.php?kategori_ID=" . $kategori_Data['kategori_id'] . "'>
                                            <button type='button' class='btn btn-success'><i class='bi bi-pen'></i></button>
                            </a> 
                            </td>";

                                    echo "<td>
                            <a href='delete.php?id=" . $kategori_Data['kategori_id'] . "'>
                                            <button type='button' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                            </a>
                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Tidak ada data kategori</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            </main>
            <?php include('../include/footer.php'); ?>
        </div>
    </div>
    <?php include('../include/jsscript.php'); ?>
</body>

</html>