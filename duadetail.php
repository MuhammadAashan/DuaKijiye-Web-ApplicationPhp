<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'user') {
    header('location:login.php');
    exit; // Stop further execution
}
?>
<?php require "db/db.php" ?>
<?php require "includes/header.php" ?>
<?php require "functions/function.php" ?>
<?php
// Check if the ID parameter is provided in the URL
if (isset($_GET['id'])) {
    // Get the dua ID from the URL
    $duaId = $_GET['id'];

    // Query to fetch dua details from the database based on ID
    $query = "SELECT dua.id AS dua_id,
    dua.urdu_translation,
    dua.english_translation,
    dua.arabic_translation,
    dua.transliteration,
    dua.audiolink,
    remainders.id AS remainder_id,
    remainders.user_id As remainder_user,
    favoritedua.id AS favoritedua_id,
    favoritedua.user_id As favorite_user,
    dua_user_counters.count As dua_count

FROM dua
LEFT JOIN remainders ON dua.id = remainders.dua_id
LEFT JOIN favoritedua ON dua.id = favoritedua.dua_id
LEFT JOIN dua_user_counters on dua.id = dua_user_counters.dua_id
WHERE dua.id = $duaId";
    $result = $con->query($query);
    $isFavorite = false;
    // Check if the query was successful and if there is at least one row
    if ($result && $result->num_rows > 0) {
        // Fetch the dua details
        $row = $result->fetch_assoc();
        $duaid = $row['dua_id'];
        $urduTranslation = $row['urdu_translation']; // Assuming you want the Urdu translation
        $englishTranslation = $row['english_translation']; // Assuming you want the English translation
        $arabicTranslation = $row['arabic_translation']; // Assuming you want the Arabic translation
        $transliteration = $row['transliteration'];
        $count = $row['dua_count']; // Default value
        if ($row['favorite_user'] !== null) {
            $isFavorite = true;
        } // Assuming you want the transliteration

        // Output the dua details in a card format
        echo '<div class="container my-3">';
        echo '<div class="row justify-content-center">';
        echo '<div class="col-md-8">';
        echo '<div class="card text-center" style="background-color: #E9E4E4; border: none;">';
        echo '<div class="card-body">';
        echo "<h1 class='card-text' id='urdu'>$urduTranslation</h1>";
        echo "<h4 class='card-text'>$englishTranslation</h4>";
        echo "<h1 class='card-text' id='urdu'>$arabicTranslation</h1>";
        echo "<h4 class='card-text mb-5'>$transliteration</h4>";
        echo "<button class='btn btn-outline-danger mx-2 favorite-btn' data-dua-id='$duaid'>";
        echo "<i class='bi bi-heart" . ($isFavorite ? '-fill' : '') . "'></i> ";
        echo "Favorite ";
        echo "<span class='badge bg-secondary'></span>";
        echo "</button>";

        echo '<button class="btn btn-outline-primary mx-2 counter-btn mb-2" data-dua-id="' . $duaId . '">';
        echo '    <i class="bi bi-calculator"></i> Counter <span class="badge bg-secondary" id="counterBadge">' . $count . '</span>';
        echo '</button>';
        if ($count > 0) {
            echo '            <!-- Reset counter button -->';
            echo '            <button class="btn btn-outline-danger reset-counter-btn mb-2" data-dua-id="' . $duaId . '">';
            echo '                <i class="bi bi-trash"></i> Reset Counter';
            echo '            </button>';
        }
        // Reminder button
        echo "<a href='remainderset.php'><button class='btn btn-outline-warning mx-2'><i class='bi bi-bell'></i> Reminder <span class='badge bg-secondary'></span></button></a> ";
        echo '<button class="btn btn-outline-secondary speaker-icon" data-audio-url="' . 'uploads/' . $row['audiolink'] . '">';
        echo '    <i class="bi bi-volume-up"></i> Play Audio';
        echo '</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        // Dua not found or error in query
        echo "<h4>Dua not found</h4>";
    }
} else {
    // No ID provided in the URL
    echo "<h4>No dua selected</h4>";
}
?>
<script>
   // Add event listener to speaker icons
document.querySelectorAll('.speaker-icon').forEach(icon => {
    icon.addEventListener('click', function() {
        const audioUrl = this.getAttribute('data-audio-url');

        // Create an audio element
        const audio = new Audio(audioUrl);

        // Play the audio
        audio.play();

        // Remove the event listener to prevent multiple clicks
        icon.removeEventListener('click', arguments.callee);
    });
});

</script>
<script>
    // Function to handle favorite/unfavorite action
    // Function to handle favorite/unfavorite action
    function toggleFavorite(duaId) {
        // Send an AJAX request to the server to handle favorite/unfavorite action
        // You need to implement this part on the server-side
        // Example AJAX request:
        fetch('functions/favorite.php', {
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
                // Check if the dua is now favorited or unfavorited
                if (data.is_favorite) {
                    // Dua is now favorited, add filled heart icon
                    document.querySelector('.favorite-btn[data-dua-id="' + duaId + '"] i').classList.add('bi-heart-fill');
                    location.reload();
                } else {
                    // Dua is now unfavorited, remove filled heart icon
                    document.querySelector('.favorite-btn[data-dua-id="' + duaId + '"] i').classList.remove('bi-heart-fill');
                }
            })
            .catch(error => console.error('Error:', error));
    }


    // Add event listener to favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function() {
            const duaId = this.getAttribute('data-dua-id');
            toggleFavorite(duaId);
        });
    });
</script>
<script>
    document.querySelectorAll('.counter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const duaId = this.getAttribute('data-dua-id');
            const userId = <?php echo $_SESSION['userId']; ?>; // Retrieve the session user ID
            // Send an AJAX request to counter.php
            fetch('functions/counter.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        dua_id: duaId,
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response
                    console.log(data);
                    if (data.success) {
                        // Update the button text or inner HTML with the new count value
                        const count = data.count; // Assuming the count value is returned in the response
                        const badge = button.querySelector('.badge');
                        badge.textContent = count;
                    } else {
                        // Handle the error if needed
                        console.error('Failed to update count:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>

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


<?php require "includes/footer.php" ?>