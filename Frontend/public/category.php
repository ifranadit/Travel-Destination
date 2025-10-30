<?php
if (!defined('aktif')) {
    die('anda tidak bisa akses langsung file ini');
} else {

    include(__DIR__ . "/../../AdminDashboard/includes/config.php");

    // Query untuk mengambil data secara acak per kategori
    $query = mysqli_query($conn, "
        SELECT * 
        FROM kategori, kabupaten, destinasi 
        WHERE kategori.kategori_id = destinasi.kategori_id 
        AND kabupaten.kabupaten_id = destinasi.kabupaten_id
        GROUP BY kategori.kategori_id
        ORDER BY RAND()
        LIMIT 1
    ");
?>
    <section class="pt-5 pt-md-9" id="service">
        <div class="container">
            <div class="position-absolute z-index--1 end-0 d-none d-lg-block">
                <img src="assets/img/category/shape.svg" style="max-width: 200px" alt="service" />
            </div>
            <?php if (mysqli_num_rows($query) > 0) { ?>
                <?php while ($row = mysqli_fetch_array($query)) { ?>
                    <div class="mb-7 text-center">
                        <h5 class="text-secondary">CATEGORY</h5>
                        <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">
                            <?php echo $row['kategori_NAMA']; ?>
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 mb-6">
                            <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                                <div class="card-body p-xxl-5 p-4">
                                    <img src="assets/img/category/icon1.png" width="75" alt="Service" />
                                    <h4 class="mb-3"><?php echo $row['destinasi_nama']; ?></h4>
                                    <p class="mb-0 fw-medium"><?php echo $row['destinasi_keterangan']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div><!-- end of .container-->
    </section>
<?php } ?>




<!-- <div class="col-lg-3 col-sm-6 mb-6">
                            <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                                <div class="card-body p-xxl-5 p-4"> <img src="assets/img/category/icon2.png" width="75" alt="Service" />
                                    <h4 class="mb-3">Best Flights</h4>
                                    <p class="mb-0 fw-medium">Engrossed listening. Park gate sell they west hard for the.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-6">
                            <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                                <div class="card-body p-xxl-5 p-4"> <img src="assets/img/category/icon3.png" width="75" alt="Service" />
                                    <h4 class="mb-3">Local Events</h4>
                                    <p class="mb-0 fw-medium">Barton vanity itself do in it. Preferd to men it engrossed listening.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-6">
                            <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                                <div class="card-body p-xxl-5 p-4"> <img src="assets/img/category/icon4.png" width="75" alt="Service" />
                                    <h4 class="mb-3">Customization</h4>
                                    <p class="mb-0 fw-medium">We deliver outsourced aviation services for military customers</p>
                                </div>
                            </div>
                        </div> -->