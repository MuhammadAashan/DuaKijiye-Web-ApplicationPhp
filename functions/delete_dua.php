<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data as JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Check if duaId is provided in the POST data
    if (isset($data["duaId"])) {
        // Establish database connection (replace with your database credentials)
        $pdo = new PDO('mysql:host=localhost;dbname=dua_kijiye', 'root', '');
        $duaId = $data["duaId"];
        $sql = "DELETE FROM dua WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        // Execute the deletion query
        $stmt->execute([$duaId]);
        // Respond with a success message
        echo json_encode(["success" => true]);
    } else {
        // Respond with an error message if duaId is not provided
        echo json_encode(["error" => "Dua ID is not provided"]);
    }
} else {
    // Respond with an error message if the request method is not POST
    echo json_encode(["error" => "Invalid request method"]);
}
?>
