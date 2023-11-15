<?php require_once __DIR__.'/partials-front/menu.php' ;?>

<section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Login.</h2>

            <form action="" method="post" class="order">
                
                <fieldset>
                    <legend>Login Details</legend>
                    <?php
                    echo $_SESSION['user-login'] ?? '';
                    unset($_SESSION['user-login']);

                    echo $_SESSION[''] ?? '';
                    unset($_SESSION['']);
                    ?>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. john@mail.com" class="input-responsive" required>
                    <?php 
                        echo $_SESSION['email-exists'] ?? '';
                        unset($_SESSION['email-exists']);
                    ?>

                    <div class="order-label">Password</div>
                    <input type="password" name="password" minlength="6" placeholder="Enter password" class="input-responsive" required>

                    <button type="submit" class="btn btn-primary">Login</button><span>Don't have an account? <a href="register.php" style="color: white;">Sign up!</a></span>
                </fieldset>
            </form>
        </div>
    </section>
<?php require_once __DIR__.'/partials-front/footer.php' ?> 

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Harvesting the data from the database
    $email = $_POST['email'];
    $password = $_POST['password'];

    // We'll need to encrypt password to match with the stored one in the database
    $password = md5($password);

    // Retrieving data from the database for cross-checking with our user input 
    $query1 = $pdo->prepare("
        SELECT * FROM users WHERE email=:email AND password=:password
    ");
    $query1->bindValue(':email', $email);
    $query1->bindValue(':password', $password);
    $query1->execute();
    $user = $query1->fetch(PDO::FETCH_ASSOC);
    if (empty($user)){
        $_SESSION['user-login'] = '<div class="error text-center">Failed to login</div>';
        header('Location: '.SITEURL.'login.php');
        exit;
    }else{
        $_SESSION['authorized'] = 'success';
        header('Location: '.SITEURL);
        exit;
    }
}
