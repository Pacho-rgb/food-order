<?php
// Adding the sessions to every page.
session_start(); 

// Making our home url as a constant
define('SITEURL', 'http://localhost/food-order-copy/');

// We should make the connection to the database
// We'll connect to the database using the PDO  object
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=food-order-copy', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


