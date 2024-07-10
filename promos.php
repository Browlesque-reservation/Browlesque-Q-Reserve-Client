<?php
// define('INCLUDED', true);
require_once('connect.php');

$admin_upload_path = '../Browlesque-Q-Reserve/'; // Update this path accordingly

$query = "SELECT promo_id, promo_details, promo_price, promo_path, promo_type FROM promo WHERE promo_state = 'Activated'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Browlesque</title>
    <link rel="icon" href="assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <!-- CSS Link -->
    <link rel="stylesheet" href="Assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Box Icon Link for Icons -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
<body>
  
  <?php include_once('topnavbar.php') ?>

<div class="container-fluid">
  <div class="container-flex add-white-bg">
    <div class="promos-container container">
        <div class="head-promos text-center"> Just for you promos!</div>
        <?php
          // Check if there are any promos
          if (mysqli_num_rows($result) > 0) {
             // Loop through each promo
            while ($row = mysqli_fetch_assoc($result)) {
              $promo_id = $row['promo_id'];
              $promo_details = $row['promo_details'];
              $promo_price = $row['promo_price'];
              $promo_path = $row['promo_path'];
        ?>
          <div class="promos-box">
            <img class="promo-bg" src="<?php echo $admin_upload_path . $promo_path; ?>" alt="Promo Image">
            <div class="promo-details" id="promo_details"><?php echo $promo_details; ?></div>
            <div class="promo-price" id="promo_price"><?php echo 'Price: ₱', $promo_price; ?></div>
          </div>
            <?php }
              } else {
                  echo "No promos found.";
              }
            ?>
    </div>
  </div>

</div>

<?php include_once('footer.php') ?>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/text.js"></script>
<script src="./assets/js/carousel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>