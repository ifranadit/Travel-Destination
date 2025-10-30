<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}
// include database connection file
include("../includes/config.php");

// Check if form is submitted for user update, then redirect to homepage after update
if (isset($_POST['update'])) {
    $kategori_ID = $_POST['input_ID'];
    $kategori_NAMA = $_POST['input_Katnama'];
    $kategori_KET = $_POST['input_Katket'];

    // update user data
    $result = mysqli_query($conn, "UPDATE kategori SET kategori_NAMA='$kategori_NAMA', kategori_KET='$kategori_KET' WHERE kategori_id='$kategori_ID'");

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected user data based on id
if (isset($_GET['kategori_ID'])) {
    $kategori_ID = $_GET['kategori_ID'];

    // Fetch user data based on id
    $result = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_id='$kategori_ID'");

    if (mysqli_num_rows($result) > 0) {
        $kategori_DATA = mysqli_fetch_array($result);
        $kategori_NAMA = $kategori_DATA['kategori_NAMA'];
        $kategori_KET = $kategori_DATA['kategori_KET'];
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
                    <form action="edit.php" method="POST" class="mt-5">
                        <div class="mb-3">
                            <label for="kategori_ID" class="form-label">Berita ID</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $kategori_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kategori_NAMA" class="form-label">Kategori Nama</label>
                            <input type="text" class="form-control" name="input_Katnama" value="<?php echo $kategori_NAMA; ?>">
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="input_Katket"><?php echo $kategori_KET; ?></textarea>
                            <label for="kategori_KET">Kategori Keterangan</label>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>
                    <?php
                    $searchQuery = "SELECT * FROM kategori";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE kategori_NAMA LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY kategori_id ASC";
                    $result = mysqli_query($conn, $searchQuery);
                    ?>

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
            <?php include('../include/footer.php'); ?>
        </div>
    </div>
    <?php include('../include/jsscript.php'); ?>
</body>

</html>