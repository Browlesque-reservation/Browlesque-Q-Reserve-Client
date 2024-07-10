<?php
session_start();
require_once('connect.php'); // Include your database connection script

function convertToWebP($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    $isConverted = false;

    if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
        $image = imagecreatefromjpeg($source);
        $isConverted = imagewebp($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepalettetotruecolor($image); // Ensures PNG with palette-based images are handled
        $isConverted = imagewebp($image, $destination, $quality);
    } else {
        return false; // Unsupported file type
    }

    imagedestroy($image);
    return $isConverted;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $clientDate = $_POST['client_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $clientName = $_POST['client_name'];
    $clientContactNo = $_POST['client_contactno'];
    $clientEmail = $_POST['client_email'];
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
            // Handle file upload
            $uploadDir = 'gcash/'; // Directory where uploaded files will be saved
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                $_SESSION['error_message'] = 'Failed to create upload directory.';
                echo json_encode(['error' => 'directory_creation_failed']);
                exit();
            }

            $uploadFile = $uploadDir . basename($_FILES['gcash_upload']['name']);
            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

            // Convert uploaded file to WebP
            $convertedFile = $uploadDir . pathinfo($uploadFile, PATHINFO_FILENAME) . '.webp';
            if (!convertToWebP($_FILES['gcash_upload']['tmp_name'], $convertedFile)) {
                $_SESSION['error_message'] = 'Error converting file to WebP.';
                echo json_encode(['error' => 'conversion_failed']);
                exit();
            }

            // Validate converted file type
            $allowedTypes = ['webp'];
            $imageFileType = strtolower(pathinfo($convertedFile, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, $allowedTypes)) {
                $_SESSION['error_message'] = 'Invalid file type. Only WebP files are allowed.';
                echo json_encode(['error' => 'invalid_file_type']);
                exit();
            }

            // Prepare and execute SQL INSERT statement for client_appointment
            $query = "INSERT INTO client_appointment (service_id, promo_id, client_date, start_time, end_time, terms_conditions, status, ip_address, image_path, image_type) 
                      VALUES (?, ?, ?, ?, ?, ?, 'For Verification', ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssss", $serviceIDs, $promoIDs, $clientDate, $startTime, $endTime, $termsConditions, $ip_address, $convertedFile, $imageFileType);
            $result = $stmt->execute();
            
            if ($result) {
                // Get the last inserted appointment ID
                $lastAppointmentID = $stmt->insert_id;
     
                // Prepare and execute SQL INSERT statement for client_details
                $query_details = "INSERT INTO client_details (client_name, client_contactno, client_email, client_notes, appointment_id) 
                                  VALUES (?, ?, ?, ?, ?)";
                $stmt_details = $conn->prepare($query_details);
                $stmt_details->bind_param("ssssi", $clientName, $clientContactNo, $clientEmail, $clientNotes, $lastAppointmentID);
                $result_details = $stmt_details->execute();
    
                if ($result_details) {
                    $_SESSION['appointment_submitted'] = true;

                    // Delete the original uploaded file after successful insertion
                    unlink($_FILES['gcash_upload']['tmp_name']); // Keep the converted file

                    echo json_encode(['success' => true]);
                } else {
                    $_SESSION['error_message'] = 'Error inserting client details: ' . $stmt_details->error;
                    echo json_encode(['error' => 'client_details_error']);
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Error inserting appointment: ' . $stmt->error;
                echo json_encode(['error' => 'appointment_error']);
                exit();
            }
        }
    } else {
        $_SESSION['error_message'] = 'Service ID and Promo ID are required!';
        echo json_encode(['error' => 'missing_ids']);
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Invalid request method!';
    echo json_encode(['error' => 'invalid_request']);
    exit();
}
?>
