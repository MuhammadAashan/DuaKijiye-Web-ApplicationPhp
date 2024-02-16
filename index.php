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
<style>
    /* Define font styles */
    @font-face {
        font-family: urdu;
        src: url('./fonts/urdu.ttf');
    }

    .urdu-font {
        font-family: 'urdu', sans-serif;
        /* Urdu font */
        font-size: 20px;
        /* Urdu font size */
        color: #000;
        /* Urdu font color */
    }
</style>
<div class="container">
    <div class="row text-right">
        <div class="col-6">
            <h3 class="text-left mt-5">Categories</h3>
            <hr>
        </div>
        <!-- <div class="col-6 py-5 pl-5">
            <a href="adddua.php" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Add Dua
            </a>
        </div> -->
    </div>
    <div class="row mb-5">
    <?php
global $con;
$array = $con->query("SELECT * FROM category");

// Check if there are categories
if ($array->num_rows > 0) {
    while ($row = $array->fetch_assoc()) {
        $categoryId = $row['id'];
        $nameArray = json_decode($row['name'], true); // Decode JSON string into an array
        $englishName = $nameArray['name']; // Access the 'name' key in the array
        $urduName = $nameArray['urduname'];

        echo '<div class="col-md-3 col-sm-6 pb-3 text-center">
            <a href="dua.php?category_id=' . $categoryId . '" style="text-decoration: none; color: inherit;">
                <div class="card h-100" style="background-color: #E9E4E4;border:none;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="category" >
                                    <h4>' . $englishName . '</h4>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="category">
                                <h6 class="urdu-font">' . $urduName . '</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>';
    }
} else {
    // No categories found, display message
    echo '<div class="text-center"><h2>No Category found</h2></div>';
}
?>

    </div>
</div>

<?php require "includes/footer.php" ?>