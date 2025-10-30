<!DOCTYPE html>
<html>

<?php

include("includes/config.php");


if(isset($_POST['Simpan'])) {
    $kode_provinsi = $_POST['inputID'];
    $nama_provinsi = $_POST['inputNAMA'];
    $kode_destinasi = $_POST['kodeDESTINASI'];
    $kode_kabupaten = $_POST['kodeKABUPATEN'];
    
    $namafoto = $_FILES['fotoprovinsi']['name'];
    $file_tmp = $_FILES["fotoprovinsi"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/'.$namafoto);

    mysqli_query($conn, "INSERT INTO provinsi (kode_provinsi, nama_provinsi, kode_destinasi, kode_kabupaten, foto_provinsi) 
                         VALUES ('$kode_provinsi', '$nama_provinsi', '$kode_destinasi', '$kode_kabupaten', '$namafoto')");

    header("location:provinsi.php");
}


$query = mysqli_query($conn, "SELECT provinsi.*, kabupaten.kode_kabupaten, kabupaten.nama_kabupaten
                              FROM provinsi 
                              LEFT JOIN kabupaten ON provinsi.kode_kabupaten = kabupaten.kode_kabupaten");



$datadestinasi = mysqli_query($conn, "SELECT * FROM destinasiwisata");

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>
<body>


<div class="row">
<div class="col-1"></div>
<div class="col-10">

<form method="POST" enctype="multipart/form-data">
  <div class="row mb-3 mt-5">
    <label for="kodeProvinsi" class="col-sm-2 col-form-label">Kode Provinsi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kodeProvinsi" name="inputID" placeholder="Kode Provinsi Wisata"> 
    </div>
  </div>
  <div class="row mb-3">
    <label for="namaProvinsi" class="col-sm-2 col-form-label">Nama Provinsi Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="namaProvinsi" name="inputNAMA" placeholder="Nama Provinsi Wisata">
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



  <div class="form-group row mb-3">
    <label for="file" class="col-sm-2 col-form-label">Foto Provinsi</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="file" name="fotoprovinsi">
      <p class="help-block">Unggah Foto Provinsi</p>
    </div>
  </div>

  <div class="form-group row">  
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <input type="submit" class="btn btn-success" value="Simpan" name="Simpan">
        <input type="reset" class="btn btn-danger" value="Batal">
    </div>
  </div>  
</form>

<form method="post">
	<div class="form-group row mt-5">
			<label for="search" class="col-sm-2">Cari Nama Provinsi</label>
		<div class="col-sm-6">
			<input type="text" name="search" class="form-control" id="search" value="<?php
			 if(isset($_POST["search"])){
				echo $_POST["search"];}?>" placeholder="Cari Nama Provinsi">
		</div>
		<input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
	</div>
</form>

<div class="jumbotron jumbotron-fluid mt-5">
  <div class="container">
    <h1 class="display-5">Output Provinsi Wisata</h1>
  </div>
</div>

<table class="table table-striped table-success table-hover mt-5">
    <tr class="info">
        <th>Kode</th>
        <th>Nama Provinsi</th>
        <th>Kode Kabupaten</th>
        <th>Nama Kabupaten</th>
        <th>Foto Provinsi</th>
        <th colspan="2">Aksi</th>
    </tr>

    <?php while ($row = mysqli_fetch_array($query)) { ?>
        <tr class="danger">
            <td><?php echo $row['kode_provinsi']; ?> </td>
            <td><?php echo $row['nama_provinsi']; ?> </td>
            <td><?php echo $row['kode_kabupaten']; ?> </td>
            <td><?php echo $row['nama_kabupaten']; ?> </td>

            <td>
                <?php if($row['foto_provinsi'] == "") { ?>
                    <img src="images/noimage.png" width="88"/>
                <?php } else { ?>
                    <img src="images/<?php echo $row['foto_provinsi'] ?>" width="88" class="img-responsive" />
                <?php } ?>
            </td>
            <td>
                <a href="provinsi_edit.php?ubahberita=<?php echo $row["kode_provinsi"]?>" class="btn btn-success btn-sm" title="EDIT">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </td>
            <td>
                <a href="provinsi_hapus.php?hapusprovinsi=<?php echo $row["kode_provinsi"]?>" class="btn btn-danger btn-sm" title="HAPUS">
                <i class="bi bi-trash"></i>
            </td>
        </tr>
    <?php } ?>
</table>


</div>
<div class="col-1"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
</body>
</html>
