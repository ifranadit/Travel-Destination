<!DOCTYPE html>
<html>

<?php

include("includes/config.php");


if(isset($_POST['Simpan'])) {
    $kode_kabupaten = $_POST['inputID'];
    $nama_kabupaten = $_POST['inputNAMA'];
    $kode_provinsi = $_POST['inputPROVINSI'];
    $kode_kecamatan = $_POST['kodeKecamatan'];  
    
    $namafoto = $_FILES['fotokabupaten']['name']; 
    $file_tmp = $_FILES["fotokabupaten"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/'.$namafoto); 

    mysqli_query($conn, "INSERT INTO kabupaten (kode_kabupaten, nama_kabupaten, kode_provinsi, kode_kecamatan, foto_kabupaten) 
                         VALUES ('$kode_kabupaten', '$nama_kabupaten', '$kode_provinsi', '$kode_kecamatan', '$namafoto')");

    header("location:kabupaten.php");
}

$query = mysqli_query($conn, "SELECT kabupaten.*, kecamatan.kode_kecamatan, kecamatan.nama_kecamatan
                              FROM kabupaten 
                              LEFT JOIN kecamatan ON kabupaten.kode_kecamatan = kecamatan.kode_kecamatan");

$dataprovinsi = mysqli_query($conn, "SELECT * FROM provinsi");

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kabupaten</title>

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
    <label for="kodeKabupaten" class="col-sm-2 col-form-label">Kode Kabupaten</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kodeKabupaten" name="inputID" placeholder="Kode Kabupaten Wisata"> 
    </div>
  </div>
  <div class="row mb-3">
    <label for="namaKabupaten" class="col-sm-2 col-form-label">Nama Kabupaten Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="namaKabupaten" name="inputNAMA" placeholder="Nama Kabupaten Wisata">
    </div>
  </div>
  <div class="row mb-3">
    <label for="kodeKecamatan" class="col-sm-2 col-form-label">Kode Kecamatan</label>
    <div class="col-sm-10">
      <select class="form-control" id="kodeKecamatan" name="kodeKecamatan">
        <option>Pilih Kode Kecamatan</option>
        <?php 
      
        $query_kecamatan = mysqli_query($conn, "SELECT * FROM kecamatan"); 
        while($row = mysqli_fetch_array($query_kecamatan)) { ?>
          <option value="<?php echo $row["kode_kecamatan"]?>">
            <?php echo $row["kode_kecamatan"] . " - " . $row["nama_kecamatan"]?>
          </option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="form-group row mb-3">
    <label for="file" class="col-sm-2 col-form-label">Foto Kabupaten</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="file" name="fotokabupaten">
      <p class="help-block">Unggah Foto Kabupaten</p>
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

<form method="post" class="mt-5">
    <div class="form-group row">
        <label for="search" class="col-sm-2 col-form-label">Cari Nama Kabupaten</label>
        <div class="col-sm-6">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                id="search" 
                value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>" 
                placeholder="Cari Nama Kabupaten">
        </div>
        <div class="col-sm-2">
            <button type="submit" name="kirim" class="btn btn-primary">Cari</button>
        </div>
    </div>
</form>



        <table class="table table-striped table-success table-hover mt-5">
            <tr class="info">
                <th>Kode</th>
                <th>Nama Kabupaten</th>
                <th>Kode Kecamatan</th>
                <th>Nama Kecamatan</th>
                <th>Foto Kabupaten</th>
                <th colspan="2">Aksi</th>
            </tr>

            <?php while ($row = mysqli_fetch_array($query)) { ?>
                <tr class="danger">
                    <td><?php echo $row['kode_kabupaten']; ?></td>
                    <td><?php echo $row['nama_kabupaten']; ?></td>
                    <td><?php echo $row['kode_kecamatan']; ?></td>
                    <td><?php echo $row['nama_kecamatan']; ?></td>
                    <td>
                        <?php if ($row['foto_kabupaten'] == "") {
                            echo "<img src='images/noimage.png' width='88'/>";
                        } else { ?>
                            <img src="images/<?php echo $row['foto_kabupaten'] ?>" width="88" class="img-responsive" />
                        <?php } ?>
                    </td>
                    <td>
                    <a href="kabupaten_edit.php?ubahberita=<?php echo $row["kode_kabupaten"] ?>" class="btn btn-success btn-sm" title="EDIT">
    <i class="bi bi-pencil-square"></i>
</a>


                    </td>
                    <td>
                        <a href="kabupaten_hapus.php?hapuskabupaten=<?php echo $row["kode_kabupaten"]?>" class="btn btn-danger btn-sm" title="HAPUS">
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