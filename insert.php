<?php
session_start();
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

        // Get user IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $query_check = "SELECT COUNT(*) AS count FROM client_appointment 
                        WHERE ip_address = ? AND client_date >= CURDATE()";
        $stmt = $conn->prepare($query_check);
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $result_check = $stmt->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] > 0) {
            $_SESSION['error_message'] = 'You have already made a booking for today or a future date. Please try again later.';
            echo json_encode(['error' => 'booking_exists']);
            exit();
        } else {
            // Prepare and execute SQL INSERT statement for client_appointment
            $query = "INSERT INTO client_appointment (service_id, promo_id, client_date, start_time, end_time, terms_conditions, status, ip_address) 
                      VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssss", $serviceIDs, $promoIDs, $clientDate, $startTime, $endTime, $termsConditions, $ip_address);
            $result = $stmt->execute();
            
            if ($result) {
                // Get the last inserted appointment ID
                $lastAppointmentID = $stmt->insert_id;
    
                // Prepare and execute SQL INSERT statement for client_details
                $query_details = "INSERT INTO client_details (client_name, client_contactno, no_of_companions, client_notes, appointment_id) 
                                  VALUES (?, ?, ?, ?, ?)";
                $stmt_details = $conn->prepare($query_details);
                $stmt_details->bind_param("ssisi", $clientName, $clientContactNo, $noOfCompanions, $clientNotes, $lastAppointmentID);
                $result_details = $stmt_details->execute();
    
                if ($result_details) {
                    $_SESSION['appointment_submitted'] = true;
                    echo json_encode(['appointment_id' => $lastAppointmentID]);
                } else {
                    $_SESSION['error_message'] = 'Error inserting client details: ' . $stmt_details->error;
                    header('Location: index.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Error inserting appointment: ' . $stmt->error;
               header('Location: index.php');
                exit();
            }
        }
    } else {
        $_SESSION['error_message'] = 'Service ID and Promo ID are required!';
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Invalid request method!';
    header('Location: index.php');
    exit();
}
?>