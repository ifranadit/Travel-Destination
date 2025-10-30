<!DOCTYPE html>
<html lang="en">

<?php
include("includes/config.php");

$kodekecamatan = $_GET["ubahberita"];
$edit = mysqli_query($conn, "SELECT * FROM kecamatan WHERE kode_kecamatan = '$kodekecamatan'");
$row_edit = mysqli_fetch_array($edit);

if (isset($_POST['ubah'])) {
    $kode_kecamatan = $_POST['inputKODE'];
    $nama_kecamatan = $_POST['inputNAMA'];

    // Handle file upload
    $namafoto = $_FILES['fotokecamatan']['name'];
    $file_tmp = $_FILES["fotokecamatan"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    if ($namafoto == "") {
        mysqli_query($conn, "UPDATE kecamatan SET nama_kecamatan = '$nama_kecamatan' WHERE kode_kecamatan = '$kode_kecamatan'");
    } else {
        mysqli_query($conn, "UPDATE kecamatan SET nama_kecamatan = '$nama_kecamatan', foto_kecamatan = '$namafoto' WHERE kode_kecamatan = '$kode_kecamatan'");
    }
    
    header("location:kecamatan.php");
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kabupaten Wisata</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<h2>Input Kecamatan Wisata</h2>
<p>Kecamatan di jawa</p>

<form method="POST" enctype="multipart/form-data">
  <div class="row mb-3 mt-5">
    <label for="kode" class="col-sm-2 col-form-label">Kode Kecamatan</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kode" name="inputKODE" value="<?php echo $row_edit["kode_kecamatan"]?>"readonly>
    </div>
  </div>

  <div class="row mb-3">
    <label for="nama" class="col-sm-2 col-form-label">Nama Kecamatan</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama" name="inputNAMA" value="<?php echo $row_edit["nama_kecamatan"]?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="fotokecamatan" class="col-sm-2 col-form-label">Foto Kecamatan</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="fotokecamatan" name="fotokecamatan">
      <p class="help-block">Unggah Foto Kecamatan</p>
    </div>
  </div>

  <div class="form-group row">  
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <input type="submit" class="btn btn-success" value="Update" name="ubah">
        <input type="reset" class="btn btn-danger" value="Batal">
    </div>
  </div>    
</form>

<br><br>

</div>
</body>

</html>
