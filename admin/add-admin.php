<?php require_once __DIR__."/partials/menu.php"; ?>

    <div class="main-content">
        <div class="wrapper">
            <h1>Add admin</h1><br>

            <?php 
            echo $_SESSION['add'] ?? '';
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['add']);
            ?>

            <form action="" method="POST">
                <table class="tbl-30">
                    <tbody>
                        <tr>
                            <td>Full name: </td>
                            <td><input type="text" name="full_name" placeholder="Enter your name"></td>
                        </tr>
                        <tr>
                            <td>Username: </td>
                            <td><input type="text" name="username" placeholder="Enter your username"></td>
                        </tr>
                        <tr>
                            <td>Password: </td>
                            <td><input type="password" name="password" placeholder="Enter your password"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" name="submit" value="Add Admin" class="btn-secondary"></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

<?php require_once __DIR__."/partials/footer.php"; ?>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Harvest the data from the post request method then insert it into the database
    $full_name = $_POST['full_name'] ?? null; 
    $username = $_POST['username'] ?? null;
    $password = md5($_POST['password']) ?? null; //Hashing of the password. Cannot be decrypted.

    $statement = $pdo->prepare(
        "INSERT INTO
        admins (full_name, username, password)
        VALUES (:full_name, :username, :password)"
    );
    $statement->bindValue(':full_name', $full_name);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);

    // Statement execution should only be done once. 
    if ($statement->execute()){
        // Create a session variable to display message
        $_SESSION['add'] = '<div class="flash-success">Admin added successfully</div>';
        // Redirect to the manage-admin page
        header("Location: ".SITEURL."admin/manage-admin.php");
    }else{
        // Create a session variable to display message
        $_SESSION['add'] = '<div class="flash-error">Failed to add admin</div>';
        // Redirect to the add-admin page
        header("Location: ".SITEURL."admin/add-admin.php");
    }
    
}

?>