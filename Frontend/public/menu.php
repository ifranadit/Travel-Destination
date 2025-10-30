<?php
if (!defined('aktif')) {
    die('anda tidak bisa akses langsung file ini');
} else {

    include(__DIR__ . "/../../AdminDashboard/includes/config.php");
    $kategori_result = mysqli_query($conn, "SELECT * FROM kategori");
    $destinasi_result = mysqli_query($conn, "SELECT * FROM destinasi");
    $kabupaten_result = mysqli_query($conn, "SELECT * FROM kabupaten");
    $testimoni_result = mysqli_query($conn, "SELECT * FROM testimoni");
?>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block"
        data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="index.php"><img src="assets/img/logo.svg" height="34"
                    alt="logo" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"> </span></button>
            <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">

                    <li class="nav-item dropdown px-3 px-lg-0 px-xl-3">
                        <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Kategori</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 0.3rem;" aria-labelledby="navbarDropdown">
                            <?php if (mysqli_num_rows($kategori_result)) { ?>
                                <?php while ($row = mysqli_fetch_array($kategori_result)) { ?>
                                    <li><a class="dropdown-item" href="kategoriwisata.php?kodekategori=<?php echo $row["kategori_id"] ?>"><?php echo $row["kategori_NAMA"] ?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown px-3 px-lg-0 px-xl-3">
                        <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Booking</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 0.3rem;" aria-labelledby="navbarDropdown">
                            <?php if (mysqli_num_rows($kabupaten_result)) { ?>
                                <?php while ($row = mysqli_fetch_array($kabupaten_result)) { ?>
                                    <li><a class="dropdown-item" href="kabupaten.php?kode=<?php echo $row["kabupaten_id"] ?>"><?php echo $row["kabupaten_nama"] ?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown px-3 px-lg-0 px-xl-3">
                        <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Testimonial</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 0.3rem;" aria-labelledby="navbarDropdown">
                            <?php if (mysqli_num_rows($testimoni_result)) { ?>
                                <?php while ($row = mysqli_fetch_array($testimoni_result)) { ?>
                                    <li><a class="dropdown-item" href="testimoni.php?kodetestimoni=<?php echo $row["id_testimoni"] ?>"><?php echo $row["nama"] ?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown px-3 px-lg-0 px-xl-3">
                        <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Destinasi</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 0.3rem;" aria-labelledby="navbarDropdown">
                            <?php if (mysqli_num_rows($destinasi_result)) { ?>
                                <?php while ($row = mysqli_fetch_array($destinasi_result)) { ?>
                                    <li><a class="dropdown-item" href="destinasi.php?kodedestinasi=<?php echo $row["destinasi_id"] ?>"><?php echo $row["destinasi_nama"] ?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="../../AdminDashboard/loginAdmin/login.php">Login</a></li>
                    <li class="nav-item px-3 px-xl-4"><a class="btn btn-outline-dark order-1 order-lg-0 fw-medium"
                            href="../../AdminDashboard/loginAdmin/register.php">Sign Up</a></li>
                    <li class="nav-item dropdown px-3 px-lg-0"> <a
                            class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#"
                            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">EN</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:0.3rem;"
                            aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">EN</a></li>
                            <li><a class="dropdown-item" href="#!">BN</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
<?php } ?>