<?php
header("Content-Type: application/json"); // Set the Content-Type header to indicate JSON data

$con = new mysqli('localhost', 'root', '', 'dua_kijiye');

if (isset($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];
    $query = "SELECT id, dua_name FROM dua WHERE category_id = ? and user_id=0";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $categoryId);
    $statement->execute();
    $result = $statement->get_result();
    $duas = [];
    while ($row = $result->fetch_assoc()) {
        $duas[] = $row;
    }
    $statement->close();
    echo json_encode($duas); // Output the JSON-encoded data
} else {
    echo json_encode(["error" => "No category ID provided."]);
}
?>
