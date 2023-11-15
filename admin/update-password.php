<?php require_once __DIR__."/partials/menu.php"; ?>

<?php
// Harvesting the id from the get request
$id = $_GET['id'] ?? null;
if (!$id){
    header('Location: '.SITEURL.'admin/manage-admin.php');
    exit;
}

?>
<div class="main-content">
    <div class="wrapper">
        <h1>Update password</h1><br>

        <?php 
        echo $_SESSION['update-password'] ?? '';
        // We'll just unset the value of the $_SESSION['add']
        unset($_SESSION['update-password']);

        echo $_SESSION['password-not-match'] ?? '';
        // We'll just unset the value of the $_SESSION['add']
        unset($_SESSION['password-not-match']);
        ?>
    
    <form action="" method="POST">
        <table class="tbl-30">
            <tbody>
                <tr>
                    <td>Current Password: </td>
                    <td><input type="password" name="current_password" placeholder="Enter the current password"></td>
                </tr>
                <tr>
                    <td>New Password: </td>
                    <td><input type="password" name="new_password" placeholder="Enter the new password"></td>
                </tr>
                <tr>
                    <td>Confirm Password: </td>
                    <td><input type="password" name="confirm_password" placeholder="Confirm the new password"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Update Password" class="btn-secondary"></td>
                </tr>
            </tbody>
        </table>
    </form>     
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Harvesting the form data
    $current_password = md5($_POST['current_password']);
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Getting the user's current password from the database in order to compare with the input password
    // The query already compares the input password with the password stored in the database.
    // If the passwords don't match, then there won't be any data to be fetched.
    $statement = $pdo->prepare("
        SELECT * FROM admins WHERE id=:id AND password=:password
    ");
    $statement->bindValue(':id', $id);
    $statement->bindValue(':password', $current_password);
    $statement->execute();
    $admin = $statement->fetch(PDO::FETCH_ASSOC);
    // If user doesn't exist, redirect to manage admin page.
    if (!$admin){
        $_SESSION['user-not-found'] = '<div class="flash-error">User not found</div>';
        header('Location: '.SITEURL.'admin/manage-admin.php');
        exit;
    }
    // If the password matches, the we can update the password.
    // However, the new password MUST match with the confirmed password in order to update the current password.
    if ($new_password === $confirm_password){
        // Do some updating
        $statement = $pdo->prepare("
        UPDATE admins SET password=:password WHERE id=:id 
        ");
        $statement->bindValue(':id', $id);
        $statement->bindValue(':password', $new_password);
        $statement->execute();
        $_SESSION['password-update'] = '<div class="flash-success">Password updated successfully</div>';
        header('Location: '.SITEURL.'admin/manage-admin.php');
    }else{
        // Redirect to this page with the error message indicating that the passwords don't match
        $_SESSION['password-not-match'] = '<div class="flash-error">Passwords dont match</div>';
        header('Location: '.SITEURL.'admin/update-password.php?id='.$id);
    }
    
}
?>

<?php require_once __DIR__."/partials/footer.php"; ?>