<?php 
require_once __DIR__."/../config/constants.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - food order system</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="login">
        <h1 style="text-align: center;">Login</h1>
        <?php
        echo $_SESSION['login'] ?? '';
        unset($_SESSION['login']);

        echo $_SESSION['not-logged-in-message'] ?? ''; 
        // We'll just unset the value of the $_SESSION['add']
        unset($_SESSION['not-logged-in-message']);
        
        ?>
        <form action="" method="post">
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" placeholder="Enter password"><br><br>

            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Harvest all the input values fron the post request
    $username = $_POST['username'] ?? '';
    $password = md5($_POST['password']) ?? '';
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';

    // Query to check whether the username and password match the input data
    $statement = $pdo->prepare("
        SELECT * FROM admins WHERE username=:username AND password=:password
    ");
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->execute();

    $admin = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$admin){
        $_SESSION['login'] = '<div class="flash-error">Failed to login</div>';
        header("Location: ".SITEURL."admin/login.php");
    }else{
        $_SESSION['login'] = '<div class="flash-success">Logged in successfully</div>';

        // Session for the authentication & authorization (Logins/Logouts)
        $_SESSION['user'] = $username;

        header("Location: ".SITEURL."admin/");
    }
}
