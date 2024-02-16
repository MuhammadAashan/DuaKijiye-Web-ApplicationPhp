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
<h2 class='text-left mb-5'>Remainders</h2>
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dua_kijiye";
$user_id = $_SESSION['userId'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL query
$sql = "SELECT remainders.id AS reminder_id, remainders.category AS reminder_title, remainders.remainder AS reminder_content, dua.id AS dua_id, dua.dua_name AS dua_title
        FROM remainders
        INNER JOIN dua ON remainders.dua_id = dua.id
        WHERE remainders.user_id = $user_id";

// Execute query
$result = $conn->query($sql);

if ($result) {
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output data of each row
       
        echo "<ul class='list-group'>";
        while ($row = $result->fetch_assoc()) {
            // Access reminder and dua data from the current row
            $reminderId = $row['reminder_id'];
            $reminderTitle = $row['reminder_title'];
            $reminderContent = $row['reminder_content'];
            $duaId = $row['dua_id'];
            $english = json_decode($row['dua_title'], true);
            $duaTitle = $english['name']['urdu'];

            // Output reminder information within list group item
            echo "<li id='reminder_$reminderId' class='list-group-item d-flex justify-content-between align-items-center'>";
            echo "<div>";
            echo "<h5 class='mb-1'>Reminder Type: $reminderTitle</h5>";
            echo "<p class='mb-1'>Reminder Time: $reminderContent</p>";
            echo " <a href='duadetail.php?id=$duaId'><p class='mb-1 urdu-font'>Dua Name: $duaTitle</p></a>";
            echo "</div>";
            // Output delete button
            echo "<button type='button' class='btn btn-danger' onclick='deleteReminder($reminderId)'>Delete</button>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<h3 class='text-center m-5 p-5'>No reminders found</h3>";
    }
} else {
    echo "<p class='text-center'>Error executing query: " . $conn->error . "</p>";
}

// Close connection
$conn->close();
?>
<script>
    function deleteReminder(reminderId) {
        if (confirm("Are you sure you want to delete this reminder?")) {
            // Make AJAX request to delete reminder
            fetch('functions/delete_reminder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    reminder_id: reminderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If deletion is successful, remove the reminder from the DOM
                    const reminderItem = document.getElementById('reminder_' + reminderId);
                    reminderItem.parentNode.removeChild(reminderItem);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete reminder. Please try again later.');
            });
        }
    }
</script>
<?php require_once "includes/footer.php" ?>
