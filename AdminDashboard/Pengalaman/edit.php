<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./loginAdmin/login.php");
    exit;
}
// Include database connection file
include("../includes/config.php");

// Set directory to store images
$target_dir = "images/";

if (isset($_POST['update'])) {
    $pengalaman_ID = $_POST['input_ID'];
    $pengalaman_Judul = $_POST['input_PengJudul'];
    $pengalaman_SubJudul = $_POST['input_PengSubJudul'];
    $pengalaman_Keterangan = $_POST['input_PengKeterangan'];
    $pengalaman_LinkVideo = $_POST['input_PengLinkVideo'];
    $namafoto = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];

    // Handle image upload if a new file is provided
    if ($namafoto != "") {
        $target_file = $target_dir . basename($namafoto);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Delete old image if it exists
            $result = mysqli_query($conn, "SELECT foto FROM pengalaman WHERE id_pengalaman='$pengalaman_ID'");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row['foto'] && file_exists($target_dir . $row['foto'])) {
                    unlink($target_dir . $row['foto']);
                }
            }

            // Update database with the new image name and other fields
            $update_result = mysqli_query($conn, "
                UPDATE pengalaman 
                SET judul='$pengalaman_Judul', sub_judul='$pengalaman_SubJudul', keterangan='$pengalaman_Keterangan', link_video='$pengalaman_LinkVideo', foto='$namafoto' 
                WHERE id_pengalaman='$pengalaman_ID'");
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
        $update_result = mysqli_query($conn, "
            UPDATE pengalaman 
            SET judul='$pengalaman_Judul', sub_judul='$pengalaman_SubJudul', keterangan='$pengalaman_Keterangan', link_video='$pengalaman_LinkVideo' 
            WHERE id_pengalaman='$pengalaman_ID'");
        if (!$update_result) {
            echo "Failed to update data.";
            exit;
        }
    }

    // Redirect to homepage after update
    header("Location: index.php");
    exit;
}

// Display selected user data based on id
if (isset($_GET['id_pengalaman'])) {
    $pengalaman_ID = $_GET['id_pengalaman'];
    $result = mysqli_query($conn, "SELECT * FROM pengalaman WHERE id_pengalaman='$pengalaman_ID'");
    if ($result && mysqli_num_rows($result) > 0) {
        $pengalaman_Data = mysqli_fetch_assoc($result);
        $pengalaman_Judul = $pengalaman_Data['judul'];
        $pengalaman_SubJudul = $pengalaman_Data['sub_judul'];
        $pengalaman_Keterangan = $pengalaman_Data['keterangan'];
        $pengalaman_LinkVideo = $pengalaman_Data['link_video'];
        $pengalaman_Foto = $pengalaman_Data['foto'];
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
                    <form action="edit.php" method="POST" enctype="multipart/form-data" class="mt-5">
                        <div class="mb-3">
                            <label for="pengalaman_ID" class="form-label">ID Pengalaman</label>
                            <input type="text" class="form-control" name="input_ID" value="<?php echo $pengalaman_ID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="input_PengJudul" value="<?php echo $pengalaman_Judul; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="sub_judul" class="form-label">Sub Judul</label>
                            <input type="text" class="form-control" name="input_PengSubJudul" value="<?php echo $pengalaman_SubJudul; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="input_PengKeterangan"><?php echo $pengalaman_Keterangan; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="link_video" class="form-label">Link Video</label>
                            <input type="text" class="form-control" name="input_PengLinkVideo" value="<?php echo $pengalaman_LinkVideo; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto" id="foto">
                            <p class="help-block">Unggah Foto Pengalaman</p>
                            <?php if (!empty($pengalaman_Foto)) { ?>
                                <img src="<?php echo $target_dir . $pengalaman_Foto; ?>" alt="Current Image" style="max-width: 100px;">
                            <?php } ?>
                        </div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </form>

                    <br>
                    <?php
                    $searchQuery = "SELECT * FROM pengalaman";
                    if (isset($_GET['cari']) && $_GET['cari'] != "") {
                        $cari = mysqli_real_escape_string($conn, $_GET['cari']);
                        $searchQuery .= " WHERE judul LIKE '%$cari%'";
                    }
                    $searchQuery .= " ORDER BY id_pengalaman ASC";
                    $result = mysqli_query($conn, $searchQuery);
                    ?>

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