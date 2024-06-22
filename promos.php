<?php
   // define('INCLUDED', true);
   require_once('connect.php');

   $query = "SELECT promo_id, promo_details, promo_image FROM promo WHERE promo_state = 'Activated'";
   $result = mysqli_query($conn, $query);

   if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browlesque</title>
    
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom container-fluid">
    <div class="container">
        <a href="http://localhost/browlesque">
            <img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client" alt="Browlesque Logo">
        </a>
        <a class="navbar-toggler" href="Index.php" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_appointment1.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php#about_us_section">About us</a>
                </li>
            </ul>
        </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <div class="container-flex add-white-bg">
    <div class="promos-container container">
        <div class="head-promos text-center"> Just for you promos!</div>
        <?php
          // Check if there are any services
          if (mysqli_num_rows($result) > 0) {
             // Loop through each service
            while ($row = mysqli_fetch_assoc($result)) {
              $promo_id = $row['promo_id'];
              $promo_details = $row['promo_details'];
              $promo_image = $row['promo_image'];
        ?>
          <div class="promos-box">
            <img class="promo-bg" src='image.php?promo_id=<?php echo $promo_id; ?>' alt='Promo Image'>
            <div class="promo-details" id="promo_details"><?php echo $promo_details; ?></div>
          </div>
            <?php }
              } else {
                  echo "No promos found.";
                  }
            ?>
    </div>
  </div>

  

  <div class="container-flex add-black-bg">
      <div class="container">
        <div class="footer-container d-flex justify-content-between">
          <div class="contact-us">
            <span class="mb-3 mb-md-0 footer-text-white">Contact Us</span>
          </div>
          <div class="contacts">
            <ul class="nav d-flex align-items-center justify-content-end">
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/email.svg" alt="Email Icon"> browlesque@gmail.com
                </span>
              </li>
              <li class="ms-3">
                <a class="footer-text-white" href="https://www.facebook.com/BrowlesqueCavite">
                  <img src="./assets/images/icon/Facebook.svg" alt="Facebook Icon"> Browlesque Cavite
                </a>
              </li>
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/Phone.svg" alt="Phone Icon"> 09123456789
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/text.js"></script>
<script src="./assets/js/carousel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
