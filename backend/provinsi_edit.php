<!DOCTYPE html>
<html lang="en">
<?php
include("includes/config.php");

$kodeprovinsi = $_GET["ubahberita"];
$edit = mysqli_query($conn, "SELECT * FROM provinsi WHERE kode_provinsi = '$kodeprovinsi'");
$row_edit = mysqli_fetch_array($edit);

if (isset($_POST['ubah'])) {
    $kode_provinsi = $_POST['inputPROVINSI'];
    $nama_provinsi = $_POST['inputNAMA'];

    // Handle file upload
    $namafoto = $_FILES['fotoprovinsi']['name'];
    $file_tmp = $_FILES["fotoprovinsi"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    if ($namafoto == "") {
        mysqli_query($conn, "UPDATE provinsi SET nama_provinsi = '$nama_provinsi' WHERE kode_provinsi = '$kode_provinsi'");
    } else {
        mysqli_query($conn, "UPDATE provinsi SET nama_provinsi = '$nama_provinsi', foto_provinsi = '$namafoto' WHERE kode_provinsi = '$kode_provinsi'");
    }

    header("location:provinsi.php");
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

<h2>Input Provinsi Wisata</h2>
<p>Provinsi di Jawa</p>

<form method="POST" enctype="multipart/form-data">
  <div class="row mb-3 mt-5">
    <label for="kode" class="col-sm-2 col-form-label">Kode Provinsi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kode" name="inputPROVINSI" value="<?php echo $row_edit["kode_provinsi"]?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <label for="nama" class="col-sm-2 col-form-label">Nama Provinsi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama" name="inputNAMA" value="<?php echo $row_edit["nama_provinsi"]?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="kodeKabupaten" class="col-sm-2 col-form-label">Kode Kabupaten Wisata</label>
    <div class="col-sm-10">
        <select class="form-control" id="kodeKabupaten" name="kodeKABUPATEN">
            <option>Pilih Kode Kabupaten Wisata</option>
            <?php 

            $query_kabupaten = mysqli_query($conn, "SELECT * FROM kabupaten");
            while($row = mysqli_fetch_array($query_kabupaten)) { ?>
                <option value="<?php echo $row["kode_kabupaten"]?>">
                    <?php echo $row["kode_kabupaten"] . " - " . $row["nama_kabupaten"]?>
                </option>
            <?php } ?>
        </select>
    </div>
  </div>

  <div class="row mb-3">
    <label for="fotoprovinsi" class="col-sm-2 col-form-label">Foto Provinsi</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="fotoprovinsi" name="fotoprovinsi" required>
      <p class="help-block">Unggah Foto Provinsi</p>
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

</body>
</html>
