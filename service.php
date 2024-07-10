<?php
require_once('connect.php');

$admin_upload_path = '../Browlesque-Q-Reserve/'; // Update this path accordingly

// Check if service_id is provided in the URL
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    
    // Fetch service information based on service_id and service_status
    $query = "SELECT service_name, service_price, service_description, service_path, service_type FROM services WHERE service_id = $service_id AND service_state = 'Activated'";
    $result = mysqli_query($conn, $query);
    
    // Check if service is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $service_name = $row['service_name'];
        $service_price = $row['service_price'];
        $service_description = $row['service_description'];
        $service_path = $admin_upload_path . $row['service_path'];
        $service_type = $row['service_type'];
    } else {
        // Handle case where service is not found or not activated
        $service_name = "Service Not Found or Not Available";
        $service_price = "";
        $service_description = "This service does not exist or is not currently available.";
        $service_path = ""; // You can provide a default image here if needed
        $service_type = ""; // Default service type
    }
} else {
  header("Location: index.php");
  exit();
}
?>
<!doctype html>
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

<section class="gallery" id="gallery">
    <h2 class="section_title" style="padding-top: 0;padding-bottom:3%;"><?php echo $service_name; ?></h2>
    <div class="section_container">
        <div class="gallery_container">
            <div class="gallery_items">
                <!-- Display service information -->
                <?php if (!empty($service_path)) : ?>
                    <img src='<?php echo $service_path; ?>' alt='Service Image'>
                <?php endif; ?>
            </div>
            <p class="text-center"><?php echo '<strong>Price: ₱', $service_price, "</strong><br><br>", $service_description; ?></p>
        </div>
    </div>
</section>

<?php include_once('footer.php') ?>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/text.js"></script>
<script src="./assets/js/carousel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>