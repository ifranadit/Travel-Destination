<?php
include("includes/config.php");

if (isset($_GET["hapuskategori"])) {
    $kodekategori = $_GET["hapuskategori"];
    mysqli_query($conn, "DELETE FROM kategori WHERE kategori_ID = '$kodekategori'");
    echo "<script>alert('DATA BERHASIL DIHAPUS');
    document.location='kategori.php';</script>";
}
?>