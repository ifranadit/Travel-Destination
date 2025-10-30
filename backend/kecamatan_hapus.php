<?php
include("includes/config.php");

if (isset($_GET["hapuskecamatan"])) {
    $kodekecamatan = $_GET["hapuskecamatan"];
    mysqli_query($conn, "DELETE FROM kecamatan WHERE kode_kecamatan = '$kodekecamatan'");
    echo "<script>alert('DATA BERHASIL DIHAPUS');
    document.location='kecamatan.php';</script>";
}
?>