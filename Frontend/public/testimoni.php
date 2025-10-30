<?php
if (!defined('aktif')) {
    die('anda tidak bisa akses langsung file ini');
} else {
?>
    <section id="testimonial">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="mb-8 text-start">
                        <h5 class="text-secondary">Testimonials </h5>
                        <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">What people say about Us.</h3>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <?php
                include(__DIR__ . "/../../AdminDashboard/includes/config.php");
                // Database connection

                // Fetch data from the `testimoni` table
                $result = mysqli_query($conn, "SELECT * FROM testimoni");
                ?>

                <div class="col-lg-6">
                    <div class="pe-7 ps-5 ps-lg-0">
                        <div class="carousel slide carousel-fade position-static" id="testimonialIndicator" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php
                                $counter = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<button class="' . ($counter == 0 ? 'active' : '') . '" type="button" data-bs-target="#testimonialIndicator" data-bs-slide-to="' . $counter . '" aria-current="true" aria-label="Testimonial ' . $counter . '"></button>';
                                    $counter++;
                                }
                                ?>
                            </div>
                            <div class="carousel-inner">
                                <?php
                                // Reset result pointer to loop through data again
                                mysqli_data_seek($result, 0);
                                $counter = 0;

                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="carousel-item position-relative <?php echo $counter == 0 ? 'active' : ''; ?>">
                                        <div class="card shadow" style="border-radius:10px;">
                                            <div class="position-absolute start-0 top-0 translate-middle">
                                                <img class="rounded-circle fit-cover" src="../../AdminDashboard/Testimonial/images/<?php echo $row['foto_testimoni']; ?>" height="65"
                                                    width="65" alt="<?php echo $row['nama']; ?>" />
                                            </div>
                                            <div class="card-body p-4">
                                                <p class="fw-medium mb-4">
                                                    <?php echo $row['judul_testimoni']; ?>
                                                </p>
                                                <p class="fw-medium mb-4">
                                                    <?php echo $row['isi_testimoni']; ?>
                                                </p>
                                                <h5 class="text-secondary">
                                                    <?php echo $row['nama']; ?>
                                                </h5>
                                                <p class="fw-medium fs--1 mb-0">
                                                    <?php echo $row['kota_negara']; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card shadow-sm position-absolute top-0 z-index--1 mb-3 w-100 h-100"
                                            style="border-radius:10px;transform:translate(25px, 25px)"> </div>
                                    </div>
                                <?php
                                    $counter++;
                                }
                                ?>
                            </div>
                            <div
                                class="carousel-navigation d-flex flex-column flex-between-center position-absolute end-0 top-lg-50 bottom-0 translate-middle-y z-index-1 me-3 me-lg-0"
                                style="height:60px;width:20px;">
                                <button class="carousel-control-prev position-static" type="button" data-bs-target="#testimonialIndicator"
                                    data-bs-slide="prev">
                                    <img src="assets/img/icons/up.svg" width="16" alt="icon" />
                                </button>
                                <button class="carousel-control-next position-static" type="button" data-bs-target="#testimonialIndicator"
                                    data-bs-slide="next">
                                    <img src="assets/img/icons/down.svg" width="16" alt="icon" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of .container-->

    </section>
<?php } ?>