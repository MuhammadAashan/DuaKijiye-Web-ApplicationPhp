<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit; // Stop further execution
}

require "db/db.php";
require "includes/header.php";
require "functions/function.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $type = $_POST['type'];
    $time = $_POST['time'];
    $reminderData = '';

    // Based on the type, store the data accordingly
    if ($type == 'weekly') {
        $days = $_POST['weekDays'];
        $reminderData = serialize($days);
    } elseif ($type == 'monthly') {
        $date = $_POST['date'];
        $reminderData = serialize($date);
    }

    // Insert the reminder data into the database
    $query = "INSERT INTO reminders (type, reminder_data, time) VALUES (?, ?, ?)";
    $statement = $connection->prepare($query);
    $statement->bind_param("sss", $type, $reminderData, $time);
    $statement->execute();

    // Redirect to a success page or do other actions after inserting the data
    header('Location: success.php');
    exit;
}
?>

<style>
    .container {
        max-width: 500px;
        margin: 0 auto;
        margin-top: 50px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    label {
        font-weight: bold;
    }

    select,
    input[type="time"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

<div class="container">
    <h2>Create Reminder</h2>
    <form action="reminder.php" method="POST" id="reminderForm">
        <div class="form-group">
            <label for="type">Reminder Type:</label>
            <select class="form-control" name="type" id="type">
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <div id="weeklyFields" style="display: none;">
            <div class="form-group">
                <label for="weekDays">Week Days:</label>
                <select class="form-control" name="weekDays[]" id="weekDays" multiple>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            <div class="form-group">
                <label for="time">Time:</label>
                <input class="form-control" type="time" name="time" id="time">
            </div>
        </div>

        <div id="monthlyFields" style="display: none;">
            <div class="form-group">
                <label for="date">Date:</label>
                <input class="form-control" type="date" name="date" id="date">
            </div>
            <div class="form-group">
                <label for="time">Time:</label>
                <input class="form-control" type="time" name="time" id="time">
            </div>
        </div>
        <input class="btn btn-primary" type="submit" value="Set Reminder">
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#type').change(function() {
            if ($(this).val() === 'weekly') {
                $('#weeklyFields').show();
                $('#monthlyFields').hide();
            } else if ($(this).val() === 'monthly') {
                $('#monthlyFields').show();
                $('#weeklyFields').hide();
            } else {
                $('#weeklyFields').hide();
                $('#monthlyFields').hide();
            }
        });
    });
</script>

<?php require_once "includes/footer.php"; ?>
