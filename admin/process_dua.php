<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin') {
    header('location:login.php');
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $arabicText = $_POST['arabicText'];
    $urduText = $_POST['urduText'];
    $englishText = $_POST['englishText'];
    $romanArabicText = $_POST['romanArabicText'];
    $urduTranslation = $_POST['urduTranslation'];
    $englishTranslation = $_POST['englishTranslation'];
    $arabicTranslation = $_POST['arabicTranslation'];
    $romanTranslation = $_POST['romanTranslation'];
    $categoryId = $_POST['categorySelect'];

    // Check if audio file is uploaded
    if(isset($_FILES['audioFile'])) {
        // Upload audio file
        $audioFileName = $_FILES['audioFile']['name'];
        $audioFileTmpName = $_FILES['audioFile']['tmp_name'];
        $audioFileError = $_FILES['audioFile']['error'];

        if ($audioFileError === UPLOAD_ERR_OK) {
            // Define the destination directory
            $uploadDir = '../uploads/';

            // Move uploaded file to the destination directory
            if(move_uploaded_file($audioFileTmpName, $uploadDir . $audioFileName)) {
                // JSON encode the Dua name
                $duaName = '{"name":{"urdu":"' . $urduText . '","arabic":"' . $arabicText . '","roman":"' . $romanArabicText . '","english":"' . $englishText . '"}}';
                $conn = mysqli_connect("localhost", "root", "", "dua_kijiye");

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Prepare and execute the SQL query to insert Dua into database
                $sql = "INSERT INTO dua (user_id,dua_name,urdu_translation, english_translation, arabic_translation,transliteration,category_id, audiolink)
                        VALUES (0,'$duaName','$urduTranslation', '$englishTranslation', '$arabicTranslation', '$romanTranslation', '$categoryId', '$audioFileName')";

                if (mysqli_query($conn, $sql)) {
                    // Dua added successfully
                    mysqli_close($conn);
                    header('Location:home.php');
                    exit;
                } else {
                    // Dua addition failed
                    echo "Error adding Dua: " . mysqli_error($conn);
                }
            } else {
                // Handle audio file upload error
                echo "Failed to move uploaded file.";
            }
        } else {
            // Handle audio file upload error
            echo "Error uploading audio file.";
        }
    }  else {
        // Handle audio file not uploaded
        echo "No audio file uploaded.";
    }
} else {
    // Redirect or display error message if form is not submitted
    echo "Form submission error.";
}
?>