<?php
session_start();

require_once('connect.php'); // Include your database connection script

// Check if the appointment submission session variable is set
if (!isset($_SESSION['appointment_submitted']) || $_SESSION['appointment_submitted'] !== true) {
    // If not set, redirect to the booking page
    header('Location: index.php');
    exit();
}

// Get appointment_id from the URL
if (isset($_GET['appointment_id'])) {
    $appointmentId = intval($_GET['appointment_id']); // Ensure it's an integer to prevent SQL injection

    // Fetch appointment details from the database
    $query = "SELECT * FROM client_appointment WHERE appointment_id = $appointmentId";
    $result = mysqli_query($conn, $query);
    $appointment = mysqli_fetch_assoc($result);

    // Check if appointment exists
    if ($appointment) {
        // Fetch client details associated with the appointment
        $query_details = "SELECT * FROM client_details WHERE appointment_id = $appointmentId";
        $result_details = mysqli_query($conn, $query_details);
        $clientDetails = mysqli_fetch_assoc($result_details);

        // Fetch service names based on service IDs
        $serviceIds = json_decode($appointment['service_id'], true); // Decode JSON string into array
        $serviceNames = array();
        if (!empty($serviceIds)) {
            foreach ($serviceIds as $serviceId) {
                $query_service = "SELECT service_name FROM services WHERE service_id = $serviceId";
                $result_service = mysqli_query($conn, $query_service);
                $service = mysqli_fetch_assoc($result_service);
                if ($service) {
                    $serviceNames[] = $service['service_name'];
                }
            }
        }

        // Fetch promo details based on promo IDs
        $promoIds = json_decode($appointment['promo_id'], true); // Decode JSON string into array
        $promoNames = array();
        if (!empty($promoIds)) {
            foreach ($promoIds as $promoId) {
                $query_promo = "SELECT promo_details FROM promo WHERE promo_id = $promoId";
                $result_promo = mysqli_query($conn, $query_promo);
                $promo = mysqli_fetch_assoc($result_promo);
                if ($promo) {
                    $promoNames[] = $promo['promo_details'];
                }
            }
        }

        $formattedDate = date('F j, Y', strtotime($appointment['client_date']));
        $formattedStartTime = date('h:i A', strtotime($appointment['start_time']));
        $formattedEndTime = date('h:i A', strtotime($appointment['end_time']));

        // Now, you have both appointment and client details, as well as service names, you can inject them into HTML
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

    <section class="appointment" id="appointment">
        <h2 class="section_title"> Booking Confirmation</h2>
      <div class="section_container" style="padding-bottom: 20px;">
      <div class="appointment_container" style="border: 1px solid #ccc; border-radius: 20px; padding: 20px; background-color: #eeeeee;">
      <div class="container-md">
            <div class="confirmation-details"> 
                <div class="sub-text sub-text-c">Summary</div>
                <div class="sub-text sub-text-c dets mb-0">Name: <?php echo $clientDetails['client_name']; ?></div>
                <div class="sub-text sub-text-c dets mb-0 mt-0">Phone Number: <?php echo $clientDetails['client_contactno']; ?></div>
                <div class="sub-text sub-text-c dets mb-0 mt-0">No. of Companion/s : <?php echo $clientDetails['no_of_companions']; ?></div>
                <div class="sub-text sub-text-c dets mb-4">Service and Promo Availed: 
                    <?php 
                        if (!empty($serviceNames)) {
                            echo implode(', ', $serviceNames);
                        } else {
                            echo "No services availed";
                        } 
                        if (!empty($promoNames)) {
                            echo ', '.implode(', ', $promoNames);
                        } else {
                            echo ", No promos availed";
                        }
                    ?>
                </div>
                <div class="sub-text sub-text-c dets mt-0">Date and Time of Appointment: <?php echo $formattedDate; ?>. <?php echo $formattedStartTime; ?> - <?php echo $formattedEndTime; ?></div>
            </div>
            <div class="sub-text sub-text-c dets-1 mb-0 fw-bold">Trouble Downloading the QR Code?</div>
            <div class="sub-text sub-text-c dets-1 mt-0">If the QR code didn't download automatically, click the button below to download it manually.</div>
            <!-- Display the generated QR code here -->
            <div class="qrcode-container">
                  <img src="generate-qr.php?appointment_id=<?php echo $appointmentId; ?>" alt="QR Code">
            <div class="under-qrcode mt-4">
                <a id="downloadLink" href="generate-qr.php?appointment_id=<?php echo $appointmentId; ?>" download="appointment_qr_code.png">
                    <img src="./assets/images/icon/export-qr.svg" alt="QR Download Icon"> 
                </a>
            </div>

                <div class="sub-text sub-text-c dets-2">Thank you for setting your appointment.</div>
            </div>
        </div>
      </div>
      </div>
      </div>
    </section>

<script>
     window.addEventListener('load', function () {
            // Trigger the download automatically when the page loads
            document.getElementById('downloadLink').click();
        });
  
    // Variable to track whether QR code has been downloaded
    var qrDownloaded = false;

    // Event listener for the download link
    document.getElementById('downloadLink').addEventListener('click', function() {
        // Set flag to true when download link is clicked
        qrDownloaded = true;
    });

    window.addEventListener('beforeunload', function (e) {
            var confirmationMessage = 'Are you sure you want to leave this page?';
            
            // Custom message may not always appear on some browsers like Chrome, but the default one will.
            (e || window.event).returnValue = confirmationMessage; // Gecko + IE
            return confirmationMessage; // Gecko + Webkit, Safari, Chrome etc.
        });
</script>

<?php include_once('footer.php') ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
  } else {
      // Handle case where no appointment is found
      echo "<div>No appointment found.</div>";
  }
}
?>