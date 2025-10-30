<?php

    include "includes/config.php";
    if (isset($_GET['hapuskabupaten'])) {
        $kodekabupaten = $_GET["hapuskabupaten"];
        mysqli_query($conn, "DELETE FROM kabupaten WHERE kode_kabupaten = '$kodekabupaten'");
        echo "<script>alert('DATA BERHASIL DIHAPUS');
        document.location='kabupaten.php'</script>";
    }

?>