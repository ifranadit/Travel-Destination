<?php
// include database connection file
include("../includes/config.php");

// Get id from URL to delete that user
$id = $_GET['id'];

// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM destinasi WHERE destinasi_ID='$id'");

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