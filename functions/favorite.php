<?php

session_start();
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['userId'])) {
        // User is not logged in, return unauthorized status
        http_response_code(401);
        exit();
    }

    // Retrieve the dua ID from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $duaId = $data['dua_id'];
    $userId = $_SESSION['userId'];
    // Connect to the database
    $con = new mysqli('localhost', 'root', '', 'dua_kijiye');

    // Check if the dua is already favorited by the user
    $query = "SELECT * FROM favoritedua WHERE user_id = $userId AND dua_id = $duaId";
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        // Dua is already favorited, so unfavorite it
        $query = "DELETE FROM favoritedua WHERE user_id = $userId AND dua_id = $duaId";
        $con->query($query);

        $isFavorite = false; // Dua is now unfavorited
    } else {
        // Dua is not favorited, so favorite it
        $query = "INSERT INTO favoritedua (user_id, dua_id, created_at, updated_at) VALUES ($userId, $duaId, NOW(), NOW())";

        $con->query($query);

        $isFavorite = true; // Dua is now favorited
    }

    // Send response indicating the new favorite status
    echo json_encode(['is_favorite' => $isFavorite]);
} else {
    // If the request method is not POST, return method not allowed status
    http_response_code(405);
    exit();
}
