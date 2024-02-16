<?php
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve dua_id and user_id from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $duaId = $data['dua_id'];
    $userId = $data['user_id'];

    // Connect to your database
    $con = new mysqli('localhost', 'root', '', 'dua_kijiye');

    // Check if the connection was successful
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if the dua_user_counter record exists
    $checkQuery = "SELECT * FROM dua_user_counters WHERE dua_id = $duaId AND user_id = $userId";
    $checkResult = $con->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Update the existing record
        $updateQuery = "UPDATE dua_user_counters SET count = count + 1 WHERE dua_id = $duaId AND user_id = $userId";
        if ($con->query($updateQuery) === TRUE) {
            // Return success response with count value
            $countQuery = "SELECT count FROM dua_user_counters WHERE dua_id = $duaId AND user_id = $userId";
            $countResult = $con->query($countQuery);
            $count = $countResult->fetch_assoc()['count'];
            echo json_encode(['success' => true, 'count' => $count]);
        } else {
            // Return error response
            echo json_encode(['success' => false, 'error' => $con->error]);
        }
    } else {
        // Insert a new record
        $insertQuery = "INSERT INTO dua_user_counters (dua_id, user_id, count, created_at, updated_at) VALUES ($duaId, $userId, 1, NOW(), NOW())";

        if ($con->query($insertQuery) === TRUE) {
            // Return success response with count value
            echo json_encode(['success' => true, 'count' => 1]);
            
        } else {
            // Return error response
            echo json_encode(['success' => false, 'error' => $con->error]);
        }
    }

    // Close the database connection
    $con->close();
} else {
    // If the request method is not POST, return method not allowed status
    http_response_code(405);
    exit();
}
