<?php
session_start();

// Redirect users who are not logged in or not users
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit;
}

require_once "db/db.php";
require_once "includes/header.php";
require_once "functions/function.php";
echo '<div class="container mt-4">';
echo ' <h2>Dua Counters</h2>';
// Query to select all duas with counters
$query = "SELECT dua.id,dua.dua_name, dua.urdu_translation, dua.english_translation, dua.arabic_translation, dua.transliteration, dua_user_counters.count 
          FROM dua 
          LEFT JOIN dua_user_counters ON dua.id = dua_user_counters.dua_id 
          WHERE dua_user_counters.count > 0";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
    // Display each dua with a counter and reset button
    while ($row = $result->fetch_assoc()) {
        $duaId = $row['id'];
        $urduTranslation =  json_decode($row['dua_name'], true); // Dec;
        $englishTranslation = $urduTranslation['name']['urdu'];;
        $count = $row['count'];


        echo '    <div class="card">';
        echo '        <div class="card-body d-flex justify-content-between align-items-center">';
        echo '          <a href="duadetail.php?id='.$duaId.'">  <h2 class="card-title urdu-font" style="font-size: 20px;">' . $englishTranslation . '</h2></a>';

        // Display the reset button if count is greater than 0
        if ($count > 0) {
            echo '            <button class="btn btn-outline-danger reset-counter-btn" data-dua-id="' . $duaId . '">';
            echo '                <i class="bi bi-trash"></i> Reset Counter';
            echo '            </button>';
        }

        echo '        </div>'; // Close card-body
        // Close container
    }
} else {

    echo '    <div class="card m-5 p-5">';
    echo '        <div class="card-body d-flex justify-content-between align-items-center">';
    echo '<h4>No duas with counters found.</h4>';
    echo '</div>';
}
echo '    </div>'; // Close card
echo '</div>';
require_once "includes/footer.php";
?>
<script>
    document.querySelectorAll('.reset-counter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const duaId = this.getAttribute('data-dua-id');
            resetCounter(duaId);
        });
    });

    function resetCounter(duaId) {
        // Send an AJAX request to the server
        fetch('functions/reset_counter.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    dua_id: duaId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Check if the reset was successful
                if (data.success) {
                    // Optionally, you can update the UI or show a success message
                    console.log('Counter reset successfully.');
                    location.reload();
                } else {
                    // Handle the case where the reset fails
                    console.error('Failed to reset counter.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>