<?php
// define('INCLUDED', true);
require_once('connect.php');

$query = "SELECT service_id, service_name, service_description, service_image, service_state FROM services WHERE service_state = 'Activated'";
$result = mysqli_query($conn, $query);

$query1 = "SELECT promo_id, promo_details, promo_image, promo_state FROM promo WHERE promo_state = 'Activated'";
$result1 = mysqli_query($conn, $query1);



$query2 = "
SELECT ar.antecedents, ar.consequents, s1.service_image AS antecedent_image, s2.service_image AS consequent_image
FROM association_rules ar
LEFT JOIN services s1 ON ar.antecedents = s1.service_name
LEFT JOIN services s2 ON ar.consequents = s2.service_name
ORDER BY ar.conviction DESC
LIMIT 1";

$result2 = $conn->query($query2);

if ($result2->num_rows > 0) {
    // Fetch the row
    $row2 = $result2->fetch_assoc();
    $antecedents = $row2['antecedents'];
    $consequents = $row2['consequents'];
    $antecedent_image = base64_encode($row2['antecedent_image']);
    $consequent_image = base64_encode($row2['consequent_image']);
    $message = "Our " . htmlspecialchars($antecedents) . " and " . htmlspecialchars($consequents) . " Service is availed together by most of our clients! Book now and be one to experience this!";
} else {
    $antecedent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    $consequent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    $message = "We currently do not have any association rules to display. Please check back later for exciting services!";
}



if (!$result1) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Count the number of rows
$totalServices = mysqli_num_rows($result);
$totalPromos = mysqli_num_rows($result1);

// Determine if both services and promos are empty or all deactivated
$isEmpty = ($totalServices == 0 && $totalPromos == 0);
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
   

    <!-- Hero Section -->
    <section class="hero_section">
      <div class="section_container">
        <div class="hero_container">
          <div class="text_section">
            <h4>&#9733; Celebrities and Star's Choices &#9733;</h4>
            <h2>Welcome to Browlesque ~</h2>
            <p>
              Take this opportunity to have the brows and natural pinkish youthful lips you have always wanted!
            </p>

            <div class="hero_section_button">
              <a class="<?php echo $isEmpty ? 'disabled' : ''; ?>" href="<?php echo $isEmpty ? '#' : 'book_appointment.php'; ?>"> <button class="button"> BOOK NOW</button></a>
            </div>
          </div>

          <div class="image_section">
            <img src="./assets/images/pictures/face.svg" alt="face" />
          </div>
        </div>
      </div>
    </section>


    <!-- Services Section -->
    <section class="services">
      <h2 class="section_title" id="service">Our Services</h2>
      <div class="section_container">
        <div class="service_container">
        <?php
$services_found = false; // Flag to track if any services are found
// Check if there are any services
if ($totalServices > 0) {
    // Loop through each service
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the service state is 'Activated'
        if ($row['service_state'] == 'Activated') {
            $service_id = $row['service_id'];
            $service_name = $row['service_name'];
            $service_description = $row['service_description'];
            // Trim description to 50 characters and add ellipsis if it exceeds
            $trimmed_description = strlen($service_description) > 60 ? substr($service_description, 0, 60) . '...' : $service_description;
            $service_image = $row['service_image'];
            $services_found = true; // Set flag to true if at least one service is found
?>
            <div class="services_items">
                <img src='image.php?service_id=<?php echo $service_id; ?>' alt='Service Image'>
                <div class="services_text">
                    <b><?php echo $service_name; ?></b><br><br>
                    <p><?php echo $trimmed_description; ?></p>
                    <button type="button" id="read_button_<?php echo $service_id; ?>" class="btn read-button mt-4 ml-4"
                        onclick="redirectToServicePage(<?php echo $service_id; ?>)">READ MORE</button>
                </div>
            </div>
<?php
        }
    }
}
// Check if no services were found after the loop
if (!$services_found) {
    // Display message based on whether there are active promos or not
    if ($totalPromos > 0) {
        echo "<div class='no-services-message text-center'>We are very sad to inform you that there are currently no services being offered, but we still have Promos! Book now!</div>";
    } else {
        echo "<div class='no-services-message text-center'>We are very sad to inform you that there are currently no services being offered. Please come again later.</div>";
    }
}
?>

        </div>
      </div>
    </section>

    <!-- People's Choice Section -->
    <section class="gallery" id="gallery">
      <h2 class="section_title">People's Choice</h2>
      <div class="section_container">
        <div class="gallery_container">
          <div class="gallery_items">
            <img src="data:image/jpeg;base64,<?php echo $antecedent_image; ?>" alt="Gallery Image" />
          </div>
          <div class="gallery_items">
            <img src="data:image/jpeg;base64,<?php echo $consequent_image; ?>" alt="Gallery Image" />
          </div>
          <p class="text-center">
            <?php echo $message; ?>
          </p>
        </div>
      </div>
    </section>

    <div class="container-flex add-gray-bg">
            <div class="container">
                <div class="promo-container">
                    <?php
                    if ($totalPromos > 0) {
                        ?>
                        <div class="promo-welcome">
                            <h1 class="mt-2 text-center" id="just-promos">Just for you promos!</h1>
                            <div class="sub-text sub-text-r">Avail now to get quality services at a lower price</div>
                        </div>

                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php for ($i = 0; $i < $totalPromos; $i++) : ?>
                                    <button data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i; ?>"
                                        <?php echo ($i == 0) ? 'class="active"' : ''; ?>></button>
                                <?php endfor; ?>
                            </div>

                            <!-- Carousel items with dynamic data -->
                            <div class="carousel-inner">
                                <?php
                                $index = 0;
                                mysqli_data_seek($result1, 0); // Reset pointer to the beginning
                                while ($row = mysqli_fetch_assoc($result1)) :
                                    ?>
                                    <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                        <img class="d-block custom-image"
                                            src="image.php?promo_id=<?php echo $row['promo_id']; ?>"
                                            alt="<?php echo $row['promo_details']; ?>">
                                    </div>
                                    <?php
                                    $index++;
                                endwhile;
                                ?>
                            </div>

                            <!-- Carousel controls -->
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php
                } else {
                    // Display message if no services are available
                    echo "<div class='no-services-message-container d-flex justify-content-center align-items-center'>
                              <div class='no-services-message text-center'>
                                  We are very sad to inform you that there are currently no promos being offered. Please come again later.
                              </div>
                            </div>";
                }
                ?>
                </div>

                <?php if ($totalPromos > 0) : ?>
                <div class="see-more text-left"><a href="promos.php">See more information <i class='bx bx-chevron-right-circle'></i></a></div>
                <?php endif; ?>

            </div>
        </div>


    <!-- About Us Section -->
    <section class="about_us">
      <div class="section_container"  id="about_us_section">
        <div class="about_container">
          <div class="text_section">
            <h2 class="section_title">About Us</h2>
            <p>
              The Hollywood celebrities and star's choices for best Microblading eyebrows, scalp and other micropigmentation procedures.
              Take this opportunity to have the brows and natural pinkish youthful lips you have always wanted!<br><br>
              <b>Located at 12 Real Street Bacoor, Cavite, Philippines.</b>
            </p>
          </div>

          <div class="image_section">
            <img src="Assets/images/pictures/browlesque.svg" alt="browlesque" />
          </div>
        </div>
      </div>
    </section>

    <?php include_once('footer.php') ?>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="./assets/js/text.js"></script>
    <script src="./assets/js/carousel.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        function redirectToServicePage(serviceId) {
            // You can redirect the user to a specific page based on the service ID
            window.location.href = 'service.php?service_id=' + serviceId;
        }

        function scrollToAboutSection() {
            // Select the About Us section by its ID
            var aboutSection = document.getElementById('about_us_section');
            // Scroll to the About Us section with smooth behavior
            aboutSection.scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
  </body>
</html>
