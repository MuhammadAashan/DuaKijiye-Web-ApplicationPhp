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
  
   
</head>

<body>
    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-brown">
            <a class="navbar-brand" href="#">
                <img src="asset/icon.png" alt="Dua Kijiye Logo" width="50" height="50">
                Dua Kijiye
            </a>

            <button class="navbar-toggler bg-light" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav">
                <div class="mr-auto"></div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link  <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Home </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'favourite.php') ? 'active' : ''; ?>" href="favourite.php">Favourite</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'counterdetail.php') ? 'active' : ''; ?>" href="counterdetail.php">Counter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'remainder.php') ? 'active' : ''; ?>" href="remainder.php">Remainder</a>
                    </li>                   
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Account'; ?>
</a>
                        <div class="dropdown-menu dropdown-menu-right"> <!-- Add dropdown-menu-right class here -->
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
    <div class="main">
        <div class="container p-3">