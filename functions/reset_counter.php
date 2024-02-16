<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve dua_id from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $duaId = $data['dua_id'];

    // Connect to your database
    $con = new mysqli('localhost', 'root', '', 'dua_kijiye');

    // Check if the connection was successful
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Delete the counter record for the specified dua_id
    $deleteQuery = "DELETE FROM dua_user_counters WHERE dua_id = $duaId";

    if ($con->query($deleteQuery) === TRUE) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'error' => $con->error]);
    }

    // Close the database connection
    $con->close();
} else {
    // If the request method is not POST, return method not allowed status
    http_response_code(405);
    exit();
}
