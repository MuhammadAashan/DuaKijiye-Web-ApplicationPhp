<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="shortcut icon" href="asset/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <title>Dua_kijiye</title>
    <style>
        html,
        body {
            height: 100%;
            /* Set height of html and body to 100% */
            margin: 0;
            /* Remove default margin */
            padding: 0;
            /* Remove default padding */
            background-image: url('asset/26080.jpg');
            /* Replace 'path/to/your/image.jpg' with the actual path to your background image */
            background-size: cover;
            /* Ensure the background image fits inside the container */
            background-repeat: repeat;
            /* Prevent the background image from repeating */

        }
    </style>

</head>

<body>

    <div class="container-fluid p-5 text-center ">
        <div class="row justify-content-center rounded">

            <div class="col-md-4 col-sm-12 px-5 bg-light rounded" style="background: rgba(255, 255, 255, 0.8);">
                <div class="row">
                    <div class="col-12">
                        <div class="container text-center">
                            <div class="row">
                                <div class="col-12  text-center">
                                    <img src="asset/icon.png" width="50%" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="login-box">
                              <div class="alert alert-danger" id="error" style="width: 100%; margin: auto; display: none;"></div>
                            <div class=" text-center" style="width: 100%; margin: auto; padding: 20px;">
                                <h3>Login</h3>
                            </div>
                            <div style="width: 100%; margin: auto; padding: 2px;">
                                <p class="login-box-msg">Don’t have an account yet ? <a href="signup.php">Create an Account</a> </p>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                        <button type="button" onclick="togglePassword()" class="btn btn-transparent my-3 text-right"><i id="password-icon" class="bi bi-eye-slash-fill"></i>Show password</button>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
                                    </div>
                                </form>
                            </div>
                          
                            <div style="position: fixed; top: 0; width: 100%; height: 100%; z-index: -1;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php 
    session_start();
    if(isset($_SESSION['userId'])) {
        // Redirect based on user role
        if($_SESSION['role'] == 'user') {
            header('location:index.php');
            exit; // Stop further execution after redirection
        } elseif($_SESSION['role'] == 'admin') {
            header('location:admin/home.php');
            exit; // Stop further execution after redirection
        }
    }
    if (isset($_POST['login'])) {
        $user = $_POST['email'];
        $pass = $_POST['password'];
        $con = new mysqli('localhost', 'root', '', 'dua_kijiye');
        $user_query = $con->query("SELECT * FROM users WHERE email='$user' AND password='$pass'");
        $admin_query = $con->query("SELECT * FROM admins WHERE email='$user' AND password='$pass'");
        if ($user_query->num_rows > 0) {
            $data = $user_query->fetch_assoc();
            $_SESSION['userId'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            header('location:index.php');
            exit; // Stop further execution after redirection
        } elseif ($admin_query->num_rows > 0) {
            $data = $admin_query->fetch_assoc();
            $_SESSION['userId'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            header('location:admin/home.php');
            exit; // Stop further execution after redirection
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var errorElement = document.getElementById('error');
                errorElement.innerHTML = 'Login Error! Try again.';
                errorElement.style.display = 'block';
                setTimeout(function() {
                    errorElement.style.display = 'none';
                }, 3000);
            });
          </script>";
        }
    }
?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var passwordIcon = document.getElementById("password-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("bi-eye-slash-fill");
                passwordIcon.classList.add("bi-eye-fill");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("bi-eye-fill");
                passwordIcon.classList.add("bi-eye-slash-fill");
            }
        }
    </script>

</body>

</html>