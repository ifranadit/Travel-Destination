<?php
include("includes/config.php");


if (isset($_POST['Simpan'])) {
    $kode_kecamatan = $_POST['inputID'];
    $nama_kecamatan = $_POST['inputNAMA'];
    $foto_kecamatan = $_FILES['inputFOTO']['name']; 
    $file_tmp = $_FILES["inputFOTO"]["tmp_name"];

    
    move_uploaded_file($file_tmp, 'images/' . $foto_kecamatan);

    $query = "INSERT INTO kecamatan (kode_kecamatan, nama_kecamatan, foto_kecamatan) 
              VALUES ('$kode_kecamatan', '$nama_kecamatan', '$foto_kecamatan')";
    mysqli_query($conn, $query);

    
    header("Location: kecamatan.php");
    exit;
}


$query = mysqli_query($conn, "SELECT * FROM kecamatan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<form method="POST" action="kecamatan.php" enctype="multipart/form-data">
  <div class="row mb-3 mt-5">
    <label for="kodeKecamatan" class="col-sm-2 col-form-label">Kode Kecamatan Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kodeKecamatan" name="inputID" placeholder="Kode Kecamatan Wisata" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="namaKecamatan" class="col-sm-2 col-form-label">Nama Kecamatan Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="namaKecamatan" name="inputNAMA" placeholder="Nama Kecamatan Wisata" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="fotoKecamatan" class="col-sm-2 col-form-label">Foto Kecamatan Wisata</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="fotoKecamatan" name="inputFOTO" required>
    </div>
  </div>
 
  <div class="form-group row">  
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <button type="submit" class="btn btn-success" value="Simpan" name="Simpan">Simpan</button>
        <input type="reset" class="btn btn-danger" value="Batal">
    </div>
  </div>    
</form>

<form method="post">
	    <div class="form-group row mt-5">
			<label for="search" class="col-sm-2">Cari Nama Kecamatan</label>
		<div class="col-sm-6">
			<input type="text" name="search" class="form-control" id="search" value="<?php
			 if(isset($_POST["search"])){
				echo $_POST["search"];}?>" placeholder="Cari Nama Kecamatan">
		</div>
		    <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
	    </div>
</form>

<table class="table table-striped table-success table-hover mt-5">
  <thead>
    <tr class="info">
      <th>Kode</th>
      <th>Nama Kecamatan</th>
      <th>Foto Kecamatan</th>
      <th colspan="2">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
    <tr class="danger">
      <td><?php echo $row['kode_kecamatan']; ?></td>
      <td><?php echo $row['nama_kecamatan']; ?></td>
      <td>
        <?php if ($row['foto_kecamatan'] == "") { ?>
          <img src='images/noimage.png' width='88'/>
        <?php } else { ?>
          <img src="images/<?php echo $row['foto_kecamatan']; ?>" width="88" class="img-responsive"/>
        <?php } ?>
      </td>
      <!-- Move the Aksi buttons into this <tr> -->
      <td>
        <a href="kecamatan_edit.php?ubahberita=<?php echo $row["kode_kecamatan"]?>" class="btn btn-success btn-sm" title="EDIT">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
          </svg>
        </a>
      </td>
      <td>
        <a href="kecamatan_hapus.php?hapuskecamatan=<?php echo $row["kode_kecamatan"]?>" class="btn btn-danger btn-sm" title="HAPUS">
          <i class="bi bi-trash"></i>
        </a>
      </td>
    </tr>
    <?php } ?>
</tbody>

</table>
</div>
</body>

</html>

