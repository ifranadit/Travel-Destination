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
    $destinasi_ID = $_POST['input_ID'];
    $destinasi_Nama = $_POST['input_Desnama'];
    $destinasi_Alamat = $_POST['input_Desalamat'];
    $destinasi_Keterangan = $_POST['input_Desket'];
    $kecamatan_ID = $_POST['kecamatanID'];
    $kategori_ID = $_POST['kategoriID'];
    $kabupaten_ID = $_POST['kabupatenID'];
    $namafoto = $_FILES['fotokecamatan']['name'];
    $file_tmp = $_FILES['fotokecamatan']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT fotodestinasi FROM destinasi WHERE destinasi_id='$destinasi_ID'");
            $row = mysqli_fetch_assoc($result);
            if ($row['fotodestinasi'] && file_exists($target_dir . $row['fotodestinasi'])) {
                unlink($target_dir . $row['fotodestinasi']);
            }

            // Update database with the new image name
            $update_query = "UPDATE destinasi SET destinasi_nama='$destinasi_Nama', destinasi_alamat='$destinasi_Alamat', destinasi_keterangan='$destinasi_Keterangan', kecamatan_id='$kecamatan_ID', kategori_id='$kategori_ID', kabupaten_id='$kabupaten_ID', fotodestinasi='$namafoto' WHERE destinasi_id='$destinasi_ID'";
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        // If no new image, just update other fields
        $update_query = "UPDATE destinasi SET destinasi_nama='$destinasi_Nama', destinasi_alamat='$destinasi_Alamat', destinasi_keterangan='$destinasi_Keterangan', kecamatan_id='$kecamatan_ID', kateegori_id='$kategori_ID', kabupaten_id='$kabupaten_ID' WHERE destinasi_id='$destinasi_ID'";
    }

    // Execute the update query
    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
    }
}

// Display selected user data based on id
if (isset($_GET['destinasi_ID'])) {
    $destinasi_ID = $_GET['destinasi_ID'];

    // Fetch kecamatan data joined with kabupaten to display kabupaten name
    $result = mysqli_query($conn, "SELECT destinasi.*, kecamatan.kecamatan_nama FROM destinasi 
    LEFT JOIN kecamatan ON destinasi.kecamatan_id = kecamatan.kecamatan_id 
    WHERE destinasi.destinasi_id='$destinasi_ID'");

    if (mysqli_num_rows($result) > 0) {
        $destinasi_Data = mysqli_fetch_array($result);
        $kecamatan_Nama = $destinasi_Data['kecamatan_nama'];
        $kecamatan_ID = $destinasi_Data['kecamatan_id'];
        $kategori_ID = $destinasi_Data['kategori_id'];
        $kabupaten_ID = $destinasi_Data['kabupaten_id'];
        $destinasi_Nama = $destinasi_Data['destinasi_nama'];
        $destinasi_Alamat = $destinasi_Data['destinasi_alamat'];
        $destinasi_Keterangan = $destinasi_Data['destinasi_keterangan'];
        $kecamatan_foto = $destinasi_Data['fotodestinasi'];
    } else {
        echo "Data not found for the given ID.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Fetch all kecamatan data for dropdown
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
                    <form action="edit.php" method="POST" enctype="multipart/form-data" class="mt-5">

                        <div class="mb-3">
                            <label for="destinasi_ID" class="form-label">Destinasi ID</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $destinasi_ID; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="destinasi_Nama" class="form-label">Nama Destinasi</label>
                            <input type="text" class="form-control" name="input_Desnama" value="<?php echo $destinasi_Nama; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="destinasi_Alamat" class="form-label">Alamat Destinasi</label>
                            <input type="text" class="form-control" name="input_Desalamat" value="<?php echo $destinasi_Alamat; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="destinasi_Keterangan" class="form-label">Keterangan Destinasi</label>
                            <input type="text" class="form-control" name="input_Desket" value="<?php echo $destinasi_Keterangan; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="kecamatanID" class="col-form-label">Kode Kecamatan</label>
                            <select class="form-control" name="kecamatanID" id="kecamatanID" required>
                                <option value="">Pilih Kode Kecamatan</option>
                                <?php while ($row = mysqli_fetch_array($datakecamatan)) { ?>
                                    <option value="<?php echo $row["kecamatan_id"] ?>"
                                        <?php echo ($row["kecamatan_id"] == $kecamatan_ID) ? 'selected' : ''; ?>>
                                        <?php echo $row["kecamatan_id"] . " - " . $row["kecamatan_nama"]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kategoriID" class="col-form-label">Kode Kategori</label>
                            <select class="form-control" name="kategoriID" id="kategoriID" required>
                                <option value="">Pilih Kode Kategori</option>
                                <?php while ($row = mysqli_fetch_array($datakategori)) { ?>
                                    <option value="<?php echo $row["kategori_id"] ?>"
                                        <?php echo ($row["kategori_id"] == $kategori_ID) ? 'selected' : ''; ?>>
                                        <?php echo $row["kategori_id"] . " - " . $row["kategori_NAMA"]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kabupatenID" class="col-form-label">Kode Kabupaten</label>
                            <select class="form-control" name="kabupatenID" id="kabupatenID" required>
                                <option value="">Pilih Kode Kabupaten</option>
                                <?php while ($row = mysqli_fetch_array($datakabupaten)) { ?>
                                    <option value="<?php echo $row["kabupaten_id"] ?>"
                                        <?php echo ($row["kabupaten_id"] == $kabupaten_ID) ? 'selected' : ''; ?>>
                                        <?php echo $row["kabupaten_id"] . " - " . $row["kabupaten_nama"]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" class="form-control" name="fotokecamatan" id="file">
                            <p class="help-block">Unggah Foto Kecamatan</p>
                            <?php if (!empty($kecamatan_foto)) { ?>
                                <img src="<?php echo $target_dir . $kecamatan_foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>

                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>
                    <?php
                    $searchQuery = "SELECT destinasi.*, kecamatan.kecamatan_nama FROM destinasi JOIN kecamatan ON destinasi.kecamatan_id = kecamatan.kecamatan_id";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE destinasi.destinasi_nama LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY destinasi.destinasi_id ASC";
                    $query = mysqli_query($conn, $searchQuery);
                    ?>

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