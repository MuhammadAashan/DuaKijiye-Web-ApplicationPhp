<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['userId'];
    // Retrieve the language and category ID sent via AJAX
    $data = json_decode(file_get_contents('php://input'), true);
    $lang = $data['language'];
    $categoryId = $data['category_id'];
    
    // Connect to the database
    $con = new mysqli('localhost', 'root', '', 'dua_kijiye');
    
    // Prepare the query to fetch data based on category_id and language
    $query = "SELECT * FROM dua WHERE category_id = $categoryId AND (user_id = 0 OR user_id = $userId)";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $data = [];
        while($row = $result->fetch_assoc()) {
            $dua_name = json_decode($row['dua_name'], true); // Decode JSON string into an associative array

            // Access the translation based on the selected language
            $translation = $dua_name['name'][$lang];
            
            $data[] = array(
                'id' => $row['id'], // Add the ID to the data array
                'translation' => $translation // Collect dua translation
            ); // Collect dua names
        }
        
        // Return the data as JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // No duas found, return a JSON response with a message
       $data=[];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>
