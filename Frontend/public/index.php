


<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- ===============================================-->
  <!--    Document Title-->
  <!-- ===============================================-->
  <title>Web Travel</title>
  <!-- ===============================================-->
  <!--    Favicons-->
  <!-- ===============================================-->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
  <link rel="manifest" href="assets/img/favicons/manifest.json">
  <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <!-- ===============================================-->
  <!--    Stylesheets-->
  <!-- ===============================================-->
  <link href="assets/css/theme.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top">
    <?php define('aktif', TRUE); ?>
    <?php include("menu.php"); ?>
    <?php include("travel.php"); ?>

    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <?php include("category.php"); ?>
    <!-- <section> close ============================-->
    <!-- ============================================-->

    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <?php include("topdestination.php"); ?>
    <!-- <section> close ============================-->
    <!-- ============================================-->

    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <?php include("booking.php"); ?>
    <!-- <section> close ============================-->
    <!-- ============================================-->

    <?php include("plan.php"); ?>

    <?php include("radit.php"); ?>
    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <?php include("testimoni.php"); ?>
    <!-- <section> close ============================-->
    <!-- ============================================-->

    <div class="py-5 text-center">
      <p class="mb-0 text-secondary fs--1 fw-medium">All rights reserved@jadoo.co </p>
    </div>
  </main>

  
  <!-- ===============================================-->
  <!--    End of Main Content-->
  <!-- ===============================================-->

  <!-- ===============================================-->
  <!--    JavaScripts-->
  <!-- ===============================================-->
  <script src="vendors/@popperjs/popper.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.min.js"></script>
  <script src="vendors/is/is.min.js"></script>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
  <script src="vendors/fontawesome/all.min.js"></script>
  <script src="assets/js/theme.js"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap"
    rel="stylesheet">
</body>
<footer class="footer">
      <div class="footer-container">
          <div class="column quick-links">
          <h3 style="color: lightskyblue">Pesonajawa.com</h3>
          <hr width="90%;" color="lightgray" size="3">
          <p style="color: lightgray">Wisata jawa mempesona</p>
          <h3 style="color: lightskyblue">Pariwisata Solo</h3>
          <hr width="90%;" color="lightgray" size="3">
          <h3 style="color: lightskyblue">Download SLPP-App</h3>
          <hr width="90%;" color="lightgray" size="3">
          </div>

          <div class="column contact">
              <h3 style="color: lightskyblue">Informasi Kategori</h3>
              <hr width="90%;" color="lightgray" size="3">
              <br>
              <h4 style="color: white">Kode Kategori Dan Nama Kategori</h3>
              <?php include("category2.php"); ?>
          </div>



          <div class="column newsletter">
          <h3 style="color: lightskyblue">Contact Us</h3>
          <hr width="90%;" color="lightgray" size="3">
          <p style="color: lightgray">admin@pesonajawa.com</p>
          </div>
      </div>



  </footer>

</html>