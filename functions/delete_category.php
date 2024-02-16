<?php
if (isset($_GET['categoryId'])) {
    // Get the category ID from the request
    $categoryId = $_GET['categoryId'];

    // Establish database connection (you may need to replace these values with your actual database credentials)
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

    // Check if the category has any duas
    $result = $conn->query("SELECT COUNT(*) FROM dua WHERE category_id = '$categoryId'");
    $row = $result->fetch_row();
    $duasCount = $row[0];
    if ($duasCount > 0) {
        // If the category has duas, return an error message
        echo json_encode(["error" => "This category has duas. It cannot be deleted."]);
        exit;
    } else {
        // If the category has no duas, proceed with the deletion
        $deleteQuery = "DELETE FROM category WHERE id = '$categoryId'";
        if ($conn->query($deleteQuery) === TRUE) {
            // If deletion is successful, return success message
            echo json_encode(["success" => true]);
            exit;
        } else {
            // If deletion fails, return error message
            echo json_encode(["error" => "Failed to delete category. Please try again later."]);
            exit;
        }
    }
} else {
    // If category ID is not provided, return an error message
    echo json_encode(["error" => "Category ID is missing."]);
    exit;
}
?>
