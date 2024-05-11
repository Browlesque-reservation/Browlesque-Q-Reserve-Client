<?php
define('INCLUDED', true);
define('APP_LOADED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Clients</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container">
        <h1 class="page-header">List of Clients</h1>
        <?php
// Static event data
$events = [
    '2024-05-07' => 'Event 1 Details',
    '2024-05-10' => 'Event 2 Details',
    '2024-05-15' => 'Event 3 Details'
];

// Constructing the query string for the Google Calendar iframe src attribute
$queryString = http_build_query([
    'height' => 600,
    'wkst' => 1,
    'ctz' => 'Asia/Manila',
    'bgcolor' => '%23ffffff',
    'showTitle' => 0,
    'showPrint' => 0,
    'showTabs' => 0,
    'showTz' => 0,
    'color' => '%23009688'
]);

// Generate the iframe src URL with the events injected
$iframeSrc = "https://calendar.google.com/calendar/embed?{$queryString}";

foreach ($events as $date => $event) {
    $dateString = date('Ymd', strtotime($date));
    $iframeSrc .= "&dates={$dateString}/{$dateString}&details={$event}";
}
?>
        <div class="container-fluid container-md-custom-s">
            <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&ctz=Asia%2FManila&bgcolor=%23ffffff&showTitle=0&showPrint=0&showTabs=0&showTz=0&src=MDJjNjJiY2M3ZWRkNjE0OWVjOGI0NjYwNzg3NDQwOTJiMzRkNTJlOWJhNTE0ZTI0Y2NlOWYyNTIwNjJlMzliZUBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23009688" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
</div>


<script src="./assets/js/sidebar.js"></script>  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


<?php
} else {
    header("Location: index.php");
    die();
}
?>  