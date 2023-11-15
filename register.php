<?php require_once __DIR__.'/partials-front/menu.php' ;?>

    <?php
    echo $_SESSION['user-register'] ?? '';
    unset($_SESSION['user-register']);
    ?>
<section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Fill this form to register.</h2>

            <form action="" method="post" class="order">
                
                <fieldset> 
                    <legend>Registration Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="name" minlength="6" placeholder="E.g. John Doe" class="input-responsive" required>

                    <div class="order-label">Username</div>
                    <input type="text" name="username" minlength="6" placeholder="E.g. 254xxxxxx" class="input-responsive" required>
                    <?php
                     echo $_SESSION['username-exists'] ?? '';
                     unset($_SESSION['username-exists']); 
                     ?>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. john@mail.com" class="input-responsive" required>
                    <?php
                    echo $_SESSION['email-exists'] ?? '';
                    unset($_SESSION['email-exists']);
                    ?>

                    <div class="order-label">Password</div>
                    <input type="password" name="password" minlength="6" placeholder="Enter password" class="input-responsive" required>

                    <div class="order-label">Repeat password</div>
                    <input type="password" name="repeat-password" minlength="6" placeholder="Enter password" class="input-responsive" required>
                    <?php
                     echo $_SESSION['unmatched-password'] ?? '';
                     unset($_SESSION['unmatched-password']);
                    ?>

                    <button type="submit" class="btn btn-primary">Register</button><span>  Already have an account? <a style="color: white;" href="login.php">Login</a></span>
                </fieldset>

            </form>

        </div>
    </section>
<?php require_once __DIR__.'/partials-front/footer.php' ?> 

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Harvesting the user's data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat-password'];

    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    // exit;

    // Checking whether $repeat_password is the same as $password
    if ($password !== $repeat_password){
        $_SESSION['unmatched-password'] = '<div class="error">Passwords are not matching</div>';
        header('Location: '.SITEURL.'register.php');
        exit;
    }

    // We'll need to store an encrypted password to the database.
    $password = md5($password);

    // Retrieving [Username] data from the database for cross-checking with our user input
    $query = $pdo->prepare("
        SELECT * FROM users WHERE username=:username
    ");
    $query->bindValue(':username', $username);
    $query->execute();
    $db_username = $query->fetch(PDO::FETCH_ASSOC);
    if ($db_username){
        $_SESSION['username-exists'] = '<div class="error">Username already exists</div>';
        header('Location: '.SITEURL.'register.php');
        exit;
    }

    // Retrieving [Email] data from the database for cross-checking with our user input 
    $query1 = $pdo->prepare("
        SELECT * FROM users WHERE email=:email
    ");
    $query1->bindValue(':email', $email);
    $query1->execute();
    $db_email = $query1->fetch(PDO::FETCH_ASSOC);
    if ($db_email){
        $_SESSION['email-exists'] = '<div class="error">Email already exists</div>';
        header('Location: '.SITEURL.'register.php');
        exit;
    }
    
    // Storing the data into the database
    $statement = $pdo->prepare("
        INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)
    ");
    $statement->bindValue(':name', $name);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $password);

    if ($statement->execute()){
        $_SESSION['user-register'] = '<div class="success text-center">Registered and logged in successfully</div>';
        $_SESSION['authorize'] = '<div class="success text-center">Logged in successfully</div>';
        header('Location: '.SITEURL);
    }else{
        define('USER_REGISTER-FAIL', '<div class="error text-center">Failed to Registered</div>');
        header('Location: '.SITEURL.'register.php'); 
    }

}
