<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}

// include database connection file
include("../includes/config.php");

// Set directory to store images
$target_dir = "images/";

if (isset($_POST['update'])) {
    $berita_ID = $_POST['input_ID'];
    $berita_Judul = $_POST['input_Judul'];
    $berita_Isi = $_POST['input_Isi'];
    $berita_Sumber = $_POST['input_Sumber'];
    $kategoriKode = $_POST['kategoriID'];
    $namafoto = $_FILES['fotoberita']['name'];
    $file_tmp = $_FILES['fotoberita']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT berita_foto FROM berita WHERE berita_id='$berita_ID'");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row['berita_foto'] && file_exists($target_dir . $row['berita_foto'])) {
                    unlink($target_dir . $row['berita_foto']);
                }
            }

            // Update database with the new image name
            $update_result = mysqli_query($conn, "UPDATE berita SET berita_judul='$berita_Judul', berita_isi='$berita_Isi', 
            berita_sumber='$berita_Sumber', berita_foto='$namafoto', kategori_id='$kategoriKode' WHERE berita_id='$berita_ID'");
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
        $update_result = mysqli_query($conn, "UPDATE berita SET berita_judul='$berita_Judul', berita_isi='$berita_Isi', 
            berita_sumber='$berita_Sumber', kategori_id='$kategoriKode' WHERE berita_id='$berita_ID'");
        if (!$update_result) {
            echo "Failed to update data.";
            exit;
        }
    }

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected news data based on id
if (isset($_GET['berita_ID'])) {
    $berita_ID = $_GET['berita_ID'];
    $result = mysqli_query($conn, "SELECT * FROM berita WHERE berita_id='$berita_ID'");

    if ($result && mysqli_num_rows($result) > 0) {
        $berita_Data = mysqli_fetch_assoc($result);
        $berita_Judul = $berita_Data['berita_judul'];
        $berita_Isi = $berita_Data['berita_isi'];
        $berita_Sumber = $berita_Data['berita_sumber'];
        $berita_Foto = $berita_Data['berita_foto'];
        $kategoriKode = $berita_Data['kategori_id'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Fetch all categories for dropdown
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori");
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
                            <label for="input_ID" class="form-label">Berita ID</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $berita_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="input_Judul" class="form-label">Judul Berita</label>
                            <input type="text" class="form-control" name="input_Judul" value="<?php echo $berita_Judul; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="input_Isi" class="form-label">Isi Berita</label>
                            <textarea class="form-control" name="input_Isi"><?php echo $berita_Isi; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="input_Sumber" class="form-label">Sumber Berita</label>
                            <input type="text" class="form-control" name="input_Sumber" value="<?php echo $berita_Sumber; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="kategoriID" class="form-label">Kategori</label>
                            <select class="form-control" name="kategoriID">
                                <?php while ($kategori = mysqli_fetch_assoc($kategori_result)) { ?>
                                    <option value="<?php echo $kategori['kategori_id']; ?>" <?php if ($kategori['kategori_id'] == $kategoriKode) echo 'selected'; ?>>
                                        <?php echo $kategori['kategori_NAMA']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotoberita" id="file">
                            <p class="help-block">Unggah Foto Berita</p>
                            <?php if (!empty($berita_Foto)) { ?>
                                <img src="<?php echo $target_dir . $berita_Foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
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