<!DOCTYPE html>
<html>

<?php

include("includes/config.php");


if (isset($_POST['Simpan'])) {
    $id = $_POST['inputID'];
    $judul = $_POST['inputJudul'];
    $nama = $_POST['inputNama'];
    $isi_testimony = $_POST['inputIsiTestimony'];
    $kota_negara = $_POST['inputKotaNegara'];

    $foto = $_FILES['foto']['name']; 
    $file_tmp = $_FILES["foto"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/' . $foto);

    mysqli_query($conn, "INSERT INTO testimoni (id, judul, foto, nama, isi_testimony, kota_negara) 
                         VALUES ('$id', '$judul', '$foto', '$nama', '$isi_testimony', '$kota_negara')");
    header("location:testimony.php");
}


$query = mysqli_query($conn, "SELECT * FROM testimoni");
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Testimony</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Input Testimony</h1>
    <p>Form untuk memasukkan data testimony.</p>

    <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="id" class="form-label">ID</label>
        <input type="text" class="form-control" id="id" name="inputID" placeholder="ID Testimony">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" class="form-control" id="judul" name="inputJudul" placeholder="Judul Testimony">
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="inputNama" placeholder="Nama">
    </div>
    <div class="mb-3">
        <label for="isi_testimony" class="form-label">Isi Testimony</label>
        <textarea class="form-control" id="isi_testimony" name="inputIsiTestimony" rows="3" placeholder="Isi Testimony"></textarea>
    </div>
    <div class="mb-3">
        <label for="kota_negara" class="form-label">Kota-Negara</label>
        <input type="text" class="form-control" id="kota_negara" name="inputKotaNegara" placeholder="Kota, Negara">
    </div>
    <div class="mb-3">
        <label for="foto" class="form-label">Foto</label>
        <input type="file" class="form-control" id="foto" name="foto">
    </div>
    <button type="submit" class="btn btn-success" name="Simpan">Simpan</button>
    <button type="reset" class="btn btn-danger">Batal</button>
</form>


    <h2 class="mt-5">Daftar Testimony</h2>
    <table class="table table-striped table-hover mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Nama</th>
            <th>Isi Testimony</th>
            <th>Kota-Negara</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($query)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['isi_testimony']; ?></td>
                <td><?php echo $row['kota_negara']; ?></td>
                <td>
                    <?php if($row['foto'] == "") { ?>
                        <img src="images/noimage.png" width="88"/>
                    <?php } else { ?>
                        <img src="images/<?php echo $row['foto'] ?>" width="88" class="img-responsive" />
                    <?php } ?>
                </td>
                <td>
                    <a href="testimony_edit.php?ubah=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="testimony_hapus.php?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
</body>
</html>
