<?php
if (!defined('aktif')) {
    die('anda tidak bisa akses langsung file ini');
} else {
?>
    <section id="testimonial">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">

                </div>
                <div class="col-lg-1"></div>
                <?php
                include(__DIR__ . "/../../AdminDashboard/includes/config.php");
                // Database connection

                // Fetch data from the `testimoni` table
                $result = mysqli_query($conn, "SELECT * FROM radit");
                ?>

    
                            <div class="carousel-inner">
                                <?php
                                // Reset result pointer to loop through data again
                                mysqli_data_seek($result, 0);
                                $counter = 0;

                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="carousel-item position-relative <?php echo $counter == 0 ? 'active' : ''; ?>">
                                        <div class="card shadow" style="border-radius:10px;">
                                            <div class="position-absolute start-50 top-0 translate-middle">
                                                <img class="rounded-circle fit-cover" src="../../AdminDashboard/Testimonial/images/WIN_20241121_09_09_22_Pro.jpg" <?php echo $row['foto']; ?>" height="65"
                                                    width="85" alt="<?php echo $row['Yradit']; ?>" />
                                            </div>

                                              <br>
                                              <br>
                                                <center><h5 class="text-secondary">
                                                    <?php echo $row['keterangan']; ?>
                                                </h5><br></center>
                                                <center><h5 class="text-secondary">
                                                    <?php echo $row['Yradit']; ?>
                                                </h5><br></center>
                                                <center><p class="fw-medium fs--1 mb-0">
                                                    <?php echo $row['Y825230176']; ?>
                                                </p></center>
                                            </div>
                                        </div>
                       
                                <?php
                                    $counter++;
                                }
                                ?>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of .container-->

    </section>
<?php } ?>