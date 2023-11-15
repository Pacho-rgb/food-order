<?php
require_once __DIR__."/../config/constants.php";
// Here, we'll destroy all our sessions, and redirect to the login page
session_destroy();
// This is because the login system is based on the sessions.
header("Location: ".SITEURL."admin/login.php");
