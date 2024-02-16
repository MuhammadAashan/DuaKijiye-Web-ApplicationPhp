<?php 


function getAllCat()
{   
    $con = new mysqli('localhost', 'root', '', 'dua_kijiye');
    $categories = array(); // Initialize an empty array to store categories
    
    $result = $con->query("SELECT * FROM category");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Decode the JSON data for category name
            $data = json_decode($row['name'], true);
            // Append the category details to the array
            $categories[] = array(
                'id' => $row['id'],
                'name' => $data['urduname']
            );
        }
    }
    
    return $categories; // Return the array of categories
}

