<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('connect.php');

// Check if the "date" parameter is received in the POST data
if (isset($_POST['date'])) {
    // Get the date from the POST data
    $date = $_POST['date'];

    // Prepare and execute the SQL query to fetch appointments for the given date
    $sql = "SELECT client_date, start_time, end_time FROM client_appointment WHERE client_date = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch appointments and store them in an array
    $appointments = array();
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();

    // Set the response header to indicate JSON content
    header('Content-Type: application/json');

    // Encode the appointments array as JSON and echo it
    echo json_encode($appointments);
} else {
    // Set the response header to indicate JSON content
    header('Content-Type: application/json');

    // Return an error JSON response if "date" parameter is not found
    echo json_encode(array("error" => "Date parameter not found"));
}
?>
