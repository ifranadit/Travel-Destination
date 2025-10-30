<?php
include("includes/config.php");

if (isset($_GET["hapusprovinsi"])) {
    $kodeprovinsi = $_GET["hapusprovinsi"];
    mysqli_query($conn, "DELETE FROM provinsi WHERE kode_provinsi = '$kodeprovinsi'");
    echo "<script>alert('DATA BERHASIL DIHAPUS');
    document.location='provinsi.php';</script>";
}
?>