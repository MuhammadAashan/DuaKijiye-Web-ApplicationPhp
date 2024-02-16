<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    // Retrieve the category name and Urdu name from the request
    if (isset($data['categoryName'], $data['categoryUrduName'])) {
        $categoryName = $data['categoryName'];
        $categoryUrduName = $data['categoryUrduName'];
        $category = [
            "name" => $categoryName,
            "urduname" => $categoryUrduName
        ];
        $categoryjson = json_encode($category);
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dua_kijiye";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Directly insert the values into the database
        $stmt = $conn->prepare("INSERT into category  (name) values (?) ");
        $stmt->bind_param("s", $categoryjson);

        // Execute update statement
        if ($stmt->execute()) {
            // If update is successful, return success message
            echo json_encode(["success" => true]);
            exit;
        } else {
            // If update fails, return error message
            echo json_encode(["error" => "Failed to update category. Please try again later."]);
            exit;
        }
    } else {
        // If required parameters are missing, return error message
        echo json_encode(["error" => "Category name or Urdu name is missing."]);
        exit;
    }
} else {
    // If request method is not POST, return error message
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
