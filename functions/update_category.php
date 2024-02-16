<?php
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
    if (isset($data['categoryId'], $data['updatedCategoryName'], $data['updatedCategoryUrduName'])) {
       
        // Get the category ID and updated category name from the request
        $categoryId = $data['categoryId'];
        $updatedCategoryName = $data['updatedCategoryName'];
        $updatedCategoryUrduName = $data['updatedCategoryUrduName'];
        $updatedCategory = [
            "name" => $updatedCategoryName,
            "urduname" => $updatedCategoryUrduName
        ];
        // Encode the array into JSON format
        $jsonData = json_encode($updatedCategory);

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

        // Prepare update statement
        $stmt = $conn->prepare("UPDATE category SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $jsonData,  $categoryId);

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
        echo json_encode(["error" => "Required parameters are missing."]);
        exit;
    }
