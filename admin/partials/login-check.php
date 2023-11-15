<?php

// Check if the user is logged in. Authorization.
// If not logged in, redirect ot the login page.
if (!isset($_SESSION['user'])){
    $_SESSION['not-logged-in-message'] = '<div class="flash-error">Log in to access admin panel.</div>';
    header("Location: ".SITEURL."admin/login.php");
    exit;
} 