<!DOCTYPE html>
<html lang="en">
<?php
include("includes/config.php");

$kodedestinasi = $_GET["ubahberita"];
$edit = mysqli_query($conn, "SELECT * FROM destinasiwisata WHERE kode_destinasi = '$kodedestinasi'");
$row_edit = mysqli_fetch_array($edit);

if (isset($_POST['ubah'])) {
    $kode_destinasi = $_POST['inputDESTINASI'];
    $nama_destinasi = $_POST['inputNAMA'];
    $alamat_destinasi = $_POST['inputALAMAT'];
    $keterangan_destinasi = $_POST['inputKETERANGAN'];
    $kode_provinsi = $_POST['inputKODE']; // Menggunakan kode_provinsi

    $namafoto = $_FILES['fotodestinasi']['name'];
    $file_tmp = $_FILES["fotodestinasi"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    if($namafoto == ""){
        mysqli_query($conn, "UPDATE destinasiwisata SET nama_destinasi = '$nama_destinasi', alamat_destinasi = '$alamat_destinasi', keterangan_destinasi = '$keterangan_destinasi', kode_provinsi = '$kode_provinsi' WHERE kode_destinasi = '$kode_destinasi'");
    } else {
        mysqli_query($conn, "UPDATE destinasiwisata SET nama_destinasi = '$nama_destinasi', alamat_destinasi = '$alamat_destinasi', keterangan_destinasi = '$keterangan_destinasi', kode_provinsi = '$kode_provinsi', foto_destinasi = '$namafoto' WHERE kode_destinasi = '$kode_destinasi'");
    }

    header("location:destinasiwisata.php");
}

// Mengambil data dari tabel provinsi
$dataprovinsi = mysqli_query($conn, "SELECT * FROM provinsi");
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

<h2>Input Destinasi Wisata</h2>
<p>Destinasi Wisata di Jawa</p>

<form method="POST" enctype="multipart/form-data">
  <div class="row mb-3 mt-5">
    <label for="kode" class="col-sm-2 col-form-label">Kode Destinasi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kode" name="inputDESTINASI" value="<?php echo $row_edit["kode_destinasi"]?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <label for="nama" class="col-sm-2 col-form-label">Nama Destinasi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama" name="inputNAMA" value="<?php echo $row_edit["nama_destinasi"]?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="alamat" class="col-sm-2 col-form-label">Alamat Destinasi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="alamat" name="inputALAMAT" value="<?php echo $row_edit["alamat_destinasi"]?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan Destinasi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="keterangan" name="inputKETERANGAN" value="<?php echo $row_edit["keterangan_destinasi"]?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="inputKODE" class="col-sm-2 col-form-label">Kode Provinsi</label>
    <div class="col-sm-10">
      <select class="form-control" id="inputKODE" name="inputKODE" required>
        <option><?php echo $row_edit["kode_provinsi"]?></option>
        <?php if(mysqli_num_rows($dataprovinsi) > 0) { ?>
        <?php while($row = mysqli_fetch_array($dataprovinsi)) { ?>
          <option value="<?php echo $row["kode_provinsi"]?>"><?php echo $row["kode_provinsi"] . ' - ' . $row["nama_provinsi"]; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <label for="fotodestinasi" class="col-sm-2 col-form-label">Foto Destinasi</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="fotodestinasi" name="fotodestinasi" required>
      <p class="help-block">Unggah Foto Destinasi</p>
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
