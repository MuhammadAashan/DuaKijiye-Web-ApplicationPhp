<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$data = json_decode(file_get_contents('php://input'), true);
// Check if reminder_id is provided via POST
if (isset($data['reminder_id'])) {
    // Get the reminder_id from POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $reminderId = $data['reminder_id'];

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dua_kijiye";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to delete the reminder
    $sql = "DELETE FROM remainders WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ii", $reminderId, $_SESSION['userId']);

    // Execute the statement
    if ($stmt->execute()) {
        // Reminder deleted successfully
        echo json_encode(["success" => true]);
    } else {
        // Failed to delete reminder
        echo json_encode(["error" => "Failed to delete reminder. Please try again later."]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Reminder ID is not provided
    echo json_encode(["error" => "Reminder ID is missing."]);
}
} else {
    // If the request method is not POST, return method not allowed status
    http_response_code(405);
    exit();
}