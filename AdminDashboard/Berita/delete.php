<?php
// Include database connection file
include("../includes/config.php");

// Get id from URL to delete the specified record
$id = $_GET['id'];

// Execute delete query with the given id and check for success
$result = mysqli_query($conn, "DELETE FROM berita WHERE berita_ID='$id'");

if ($result) {
    // Display an alert and redirect to the homepage
    echo "<script>
        alert('DATA BERHASIL DIHAPUS');
        window.location.href = 'index.php';
    </script>";
    exit;
} else {
    // Show an error if delete operation fails
    echo "Error deleting data: " . mysqli_error($conn);
}
