<?php
define('INCLUDED', true);
require_once('connect.php');
require "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

// Retrieve data from the database
$query = "SELECT c.client_name, c.client_contactno, c.no_of_companions
          FROM client_appointment a
          INNER JOIN client_details c ON a.appointment_id = c.appointment_id
          WHERE a.appointment_id = 2"; // Adjust the condition based on your requirements
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch data from the result set
    $row = mysqli_fetch_assoc($result);
    
    // Construct text for the QR code
    $text = "Client Name: " . $row['client_name'] . "\n";
    $text .= "Client Contact No: " . $row['client_contactno'] . "\n";
    $text .= "No of Companions: " . $row['no_of_companions'] . "\n";
    // Add more fields as needed
    
    // Create QR code
    $qr_code = QrCode::create($text)
                        ->setSize(250)
                        ->setErrorCorrectionLevel(ErrorCorrectionLevel::High); // Use ErrorCorrectionLevel::HIGH
    
    // Prepare PNG writer
    $writer = new PngWriter;
    
    // Write QR code to PNG image
    $result = $writer->write($qr_code);
    
    // Set the appropriate content type
    header("Content-Type: " . $result->getMimeType());
    
    // Output the QR code image
    echo $result->getString();
} else {
    echo "No data found in the database for the specified record!";
}
?>
