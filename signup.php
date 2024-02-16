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
        <div class="row justify-content-center">

            <div class="col-md-4 col-sm-12 bg-light rounded px-5">
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
                        <div class="alert alert-success" id="success" style="width: 100%; margin: auto; display: none;"></div>
                        <div class="alert alert-danger" id="error" style="width: 100%; margin: auto; display: none;"></div>

                        <div class="login-box">
                            <div class="well well-sm text-center" style="width: 100%; margin: auto; padding: 15px;">
                                <h3>Sign Up</h3>
                            </div>
                            <div class="well well-sm" style="width: 100%; margin: auto; padding: 20px;">
                                <p class="login-box-msg">Already have an account? <a href="login.php">Sign In</a></p>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                        <button type="button" onclick="togglePassword()" class="btn btn-transparent my-3 text-right"><i id="password-icon" class="bi bi-eye-slash-fill"></i>Show password</button>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="signup" class="btn btn-primary btn-block">Sign Up</button>
                                    </div>
                                </form>
                            </div>

                            <div style="position: fixed; top: 0;width: 100%; height: 100%; z-index: -1;"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>




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
                    passwordIcon.classList.add("bi-eye-slash-fill")
                }
            }
        </script><?php
                    if (isset($_POST['signup'])) {
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $currentDateTime = date('Y-m-d H:i:s');

                        // Check if email already exists
                        $con = new mysqli('localhost', 'root', '', 'dua_kijiye');
                        $email_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
                        $result = $con->query($email_check_query);

                        if ($result->num_rows > 0) {
                            // Email already exists
                            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var errorDiv = document.getElementById("error");
                errorDiv.innerHTML = "Email already exists. Please use a different email.";
                errorDiv.style.display = "block";
                setTimeout(function() {
                    errorDiv.style.display = "none";
                }, 3000);
            });
          </script>';
                        } elseif (strlen($password) < 8) {
                            // Password must be at least 8 characters long
                            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var errorDiv = document.getElementById("error");
                errorDiv.innerHTML = "Password must be at least 8 characters long.";
                errorDiv.style.display = "block";
                setTimeout(function() {
                    errorDiv.style.display = "none";
                }, 3000);
            });
          </script>';
                        } else {
                            // Insert the new user into the database
                            $query = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('$name', '$email', '$password', '$currentDateTime', '$currentDateTime')";
                            if ($con->query($query) === TRUE) {
                                // Account created successfully
                                echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var successDiv = document.getElementById("success");
                successDiv.innerHTML = "Account created successfully. Redirecting to login page...";
                successDiv.style.display = "block";
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 3000);
            });
          </script>';
                            } else {
                                // Account creation failed
                                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var errorDiv = document.getElementById("error");
                    errorDiv.innerHTML = "Account creation failed. Please try again.";
                    errorDiv.style.display = "block";
                    setTimeout(function() {
                        errorDiv.style.display = "none";
                    }, 3000);
                });
              </script>';
                            }
                        }
                    }
                    ?>

</body>

</html>