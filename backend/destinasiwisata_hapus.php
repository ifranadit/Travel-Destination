<?php

    include "includes/config.php";
    if (isset($_GET['hapusdestinasi'])) {
        $kodedestinasi = $_GET["hapusdestinasi"];
        mysqli_query($conn, "DELETE FROM destinasiwisata WHERE kode_destinasi = '$kodedestinasi'");
        echo "<script>alert('DATA BERHASIL DIHAPUS');
        document.location='destinasiwisata.php'</script>";
    }

?>