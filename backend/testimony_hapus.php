<?php
include("includes/config.php");

if (isset($_GET["hapus"])) {
    $id = $_GET["hapus"];

    mysqli_query($conn, "DELETE FROM testimoni WHERE id = '$id'");

    echo "<script>
            alert('DATA BERHASIL DIHAPUS');
            document.location='testimony.php';
          </script>";
}
?>
