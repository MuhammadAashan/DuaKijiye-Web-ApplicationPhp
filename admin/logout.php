<?php
session_start(); // Start the session

// Check if the user is logged in
if(isset($_SESSION['userId'])){
    // Clear all session variables
    session_unset();
    
    // Destroy the session
    session_destroy();
    
    // Redirect the user to the login page
    header('Location: ../login.php');
    exit; // Stop further execution
} else {
    // If the user is not logged in, redirect them to the login page
    header('Location: ../login.php');
    exit; // Stop further execution
}
