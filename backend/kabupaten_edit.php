<!DOCTYPE html>
<html>

<?php
include("includes/config.php");

$kode_kabupaten = $_GET["ubahberita"];
$edit = mysqli_query($conn, "SELECT * FROM kabupaten WHERE kode_kabupaten = '$kode_kabupaten'");
$row_edit = mysqli_fetch_array($edit);

if (isset($_POST['ubah'])) {
    $kode_kabupaten = $_POST['inputID'];
    $nama_kabupaten = $_POST['inputNAMA'];
    $kode_provinsi = $_POST['inputPROVINSI'];
    $kode_kecamatan = $_POST['kodeKecamatan'];

    $namafoto = $_FILES['fotokabupaten']['name'];
    $file_tmp = $_FILES["fotokabupaten"]["tmp_name"];
    move_uploaded_file($file_tmp, 'images/' . $namafoto);

    if ($namafoto == "") {
        mysqli_query($conn, "UPDATE kabupaten SET nama_kabupaten = '$nama_kabupaten', kode_provinsi = '$kode_provinsi', kode_kecamatan = '$kode_kecamatan' WHERE kode_kabupaten = '$kode_kabupaten'");
    } else {
        mysqli_query($conn, "UPDATE kabupaten SET nama_kabupaten = '$nama_kabupaten', kode_provinsi = '$kode_provinsi', kode_kecamatan = '$kode_kecamatan', foto_kabupaten = '$namafoto' WHERE kode_kabupaten = '$kode_kabupaten'");
    }
    header("location:kabupaten.php");
}

$dataprovinsi = mysqli_query($conn, "SELECT * FROM provinsi");
$datakecamatan = mysqli_query($conn, "SELECT * FROM kecamatan");

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kabupaten</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>

<div class="row">
    <div class="col-1"></div>
    <div class="col-10">

        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3 mt-5">
                <label for="kodeKabupaten" class="col-sm-2 col-form-label">Kode Kabupaten</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="kodeKabupaten" name="inputID" value="<?php echo $row_edit["kode_kabupaten"] ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="namaKabupaten" class="col-sm-2 col-form-label">Nama Kabupaten</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="namaKabupaten" name="inputNAMA" value="<?php echo $row_edit["nama_kabupaten"] ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="kodeKecamatan" class="col-sm-2 col-form-label">Kode Kecamatan</label>
                <div class="col-sm-10">
                    <select class="form-control" id="kodeKecamatan" name="kodeKecamatan">
                        <option><?php echo $row_edit["kode_kecamatan"] ?></option>
                        <?php while ($row = mysqli_fetch_array($datakecamatan)) { ?>
                            <option value="<?php echo $row["kode_kecamatan"] ?>">
                                <?php echo $row["kode_kecamatan"] . " - " . $row["nama_kecamatan"] ?>
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
                    <input type="submit" class="btn btn-success" value="Update" name="ubah">
                    <input type="reset" class="btn btn-danger" value="Batal">
                </div>
            </div>
        </form>

    </div>
    <div class="col-1"></div>
</div>

</body>
</html>
