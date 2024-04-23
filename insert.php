<?php
    // define('INCLUDED', true);
    require_once('connect.php'); // Include your database connection script

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $clientDate = $_POST['client_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $clientName = $_POST['client_name'];
        $clientContactNo = $_POST['client_contactno'];
        $noOfCompanions = $_POST['no_of_companions'];
        $clientNotes = $_POST['client_notes'];
        $termsConditions = isset($_POST['terms_conditions']) ? $_POST['terms_conditions'] : 0;

        // Check if service_id and promo_id are set in $_POST
        if (isset($_POST['service_id']) && isset($_POST['promo_id'])) {
            // Get selected service IDs and promo IDs
            $serviceIDs = implode(',', $_POST['service_id']);
            $promoIDs = implode(',', $_POST['promo_id']);

            // Prepare and execute SQL INSERT statement
            $query = "INSERT INTO client_appointment (service_id, promo_id, client_date, start_time, end_time, client_name, client_contactno, no_of_companions, client_notes, terms_conditions, status) 
                      VALUES ('$serviceIDs', '$promoIDs', '$clientDate', '$startTime', '$endTime', '$clientName', '$clientContactNo', '$noOfCompanions', '$clientNotes', '$termsConditions', 'pending')";
            $result = mysqli_query($conn, $query);
        }

        header("Location: Index.php");
    } else {
        echo "Form data not submitted!";
    }
?>
