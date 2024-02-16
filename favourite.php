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

<div class="container mt-4">
    <h2>Your Favorite Duas</h2>
    <div class="row">
        <?php
        // Query to fetch favorite duas for the current user
        $userId = $_SESSION['userId'];
        $query = "SELECT dua.id, dua.dua_name, dua.urdu_translation, dua.english_translation, dua.arabic_translation 
                  FROM dua
                  INNER JOIN favoritedua ON dua.id = favoritedua.dua_id
                  WHERE favoritedua.user_id = $userId";
        $result = $con->query($query);

        if ($result && $result->num_rows > 0) {
            // Loop through each favorite dua and display it in a card
            while ($row = $result->fetch_assoc()) {
                $duaId = $row['id'];
                $englishTranslation = json_decode($row['dua_name'], true);
                $urduTranslation = $englishTranslation['name']['urdu'];
        ?>
                <div class="col-12 mb-3">
                    <div class="card d-flex flex-row align-items-center">
                        <div class="card-body">
                        <a href="duadetail.php?id=<?php echo $duaId; ?>">  <h3 class="card-title urdu-font" style="font-size: 20px!important;"><?php echo $urduTranslation; ?></h3></a> 
                        </div>

                        <!-- Heart button for favorite/unfavorite -->
                        <button class="btn btn-outline-danger favorite-btn m-2" data-dua-id="<?php echo $duaId; ?>">
                            <i class="bi bi-heart-fill"></i>
                        </button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><div class='card m-5 p-5 '><div class='card-body'><p class='card-text h4'>No favorite duas found.</p></div></div></div>";
        }
        ?>
    </div>
</div>

<!-- Add this script at the end of your HTML body -->
<script>
    // Add event listeners to heart buttons
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function() {
            const duaId = this.getAttribute('data-dua-id');
            removeFavoriteDua(duaId);
        });
    });

    // Function to remove a dua from favorites
    function removeFavoriteDua(duaId) {
        // Send an AJAX request to the server
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
                // Check if the removal was successful
                if (data.is_favorite == false) {
                    // Remove the dua from the UI
                    const duaElement = document.querySelector(`.favorite-btn[data-dua-id="${duaId}"]`).closest('.col-12');
                    duaElement.remove();
                    // Show success message
                    // alert('Dua has been unfavorited successfully.');
                    // Reload the page to refresh the favorite duas list
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<?php require "includes/footer.php" ?> 
