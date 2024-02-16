<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin') {
    header('../location:login.php');
    exit; // Stop further execution
}

require_once "../db/db.php";
require "../includes/adminheader.php";
require "../functions/function.php";

// Fetch current user information if the form is not submitted
$user = [];
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $userId = $_SESSION['userId'];
    $query = "SELECT * FROM admins WHERE id = $userId";
    $result = $con->query($query);
    $user = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Hash the password
    if (strlen($password) < 8) {
        // Password must be at least 8 characters long
        header("location:profile.php?message=invalid");
    }else{
    // Update user information
    $query = "UPDATE admins SET name = '$name', email = '$email', password = '$password' WHERE id = $userId";
    $result = $con->query($query);

    if ($result) {
        // Redirect to profile page with success message
        header("location:profile.php?message=success");
    } else {
        // Redirect to profile page with error message
        header("location:profile.php?message=error");
    }
}
}
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<div class="container mt-4">
    <!-- <h2>Profile Information</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Name: <?php echo $user['name']; ?></h5>
                    <p class="card-text">Email: <?php echo $user['email']; ?></p>
                </div>
            </div>
        </div>
    </div> -->
    <h2>Update Profile</h2>
    <div class="row">

        <div class="col-12">
            <div class="card mb-3">
                <?php if ($message == 'success') : ?>
                    <div class="alert alert-success" role="alert">
                        Profile updated successfully!
                    </div>
                <?php elseif ($message == 'error') : ?>
                    <div class="alert alert-danger" role="alert">
                        Error updating profile. Please try again.
                    </div>
                    <?php elseif ($message == 'invalid') : ?>
                    <div class="alert alert-danger" role="alert">
                        Password must be 8 characters. Please try again.
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/adminfooter.php"; ?>