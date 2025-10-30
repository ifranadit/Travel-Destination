<?php
include("includes/config.php");

    $kodekategori = $_GET["ubahberita"];
    $edit = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_ID = '$kodekategori'");
    $row_edit = mysqli_fetch_array($edit);


if (isset($_POST['ubah'])) {
    $kategori_ID = $_POST['inputID'];
    $kategori_NAMA = $_POST['inputNAMA'];
    $kategori_KET = $_POST['inputKETERANGAN'];

    mysqli_query($conn, "UPDATE kategori SET kategori_NAMA = '$kategori_NAMA', kategori_KET = '$kategori_KET' WHERE kategori_ID = '$kategori_ID'");


    header("location:kategori.php");
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori Wisata</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<form method="POST">
  <input type="hidden" name="inputID" value="<?php echo $row_edit['kategori_ID']; ?>" readonly>

  <div class="row mb-3 mt-5">
    <label class="col-sm-2 col-form-label">Nama Kategori Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="inputNAMA" value="<?php echo $row_edit['kategori_NAMA']; ?>" required>
    </div>
  </div>
  <div class="row mb-3">
    <label class="col-sm-2 col-form-label">Keterangan Kategori Wisata</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="inputKETERANGAN" value="<?php echo $row_edit['kategori_KET']; ?>" required>
    </div>
  </div>
  <div class="form-group row">  
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <button type="submit" class="btn btn-success" value="Update" name="ubah">Update</button>
        <a href="kategori.php" class="btn btn-secondary" value="Batal">Batal</a>
    </div>
  </div>    
</form>

<form method="post">
	<div class="form-group row mt-5">
			<label for="search" class="col-sm-2">Cari Nama Kategori</label>
		<div class="col-sm-6">
			<input type="text" name="search" class="form-control" id="search" value="<?php
			 if(isset($_POST["search"])){
				echo $_POST["search"];}?>" placeholder="Cari Nama Kategori">
		</div>
		<input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
	</div>
</form>

<table class="table table-striped table-success table-hover mt-5">
  <thead>
    <tr class="info">
      <th>Kode</th>
      <th>Nama Kategori</th>
      <th>Keterangan Kategori</th>
      <th colspan="2">Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php { 
          if(isset($_POST["kirim"]))
          {
            $search = $_POST["search"];
            $query = mysqli_query($conn, "select * from kategori where kategori_NAMA like '%" .$search. "%'");
          }else{
            $query = mysqli_query($conn, "select * from kategori");
          }
	    ?>
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
    <tr class="danger">
      <td><?php echo $row['kategori_ID']; ?></td>
      <td><?php echo $row['kategori_NAMA']; ?></td>
      <td><?php echo $row['kategori_KET']; ?></td>

      <td>
                        <a href="#" class="btn btn-success btn-sm" title="EDIT">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger btn-sm" title="HAPUS">
                        <i class="bi bi-trash"></i>
                    </td>
    </tr>
    <?php } ?>
    <?php } ?>
  </tbody>
</table>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>