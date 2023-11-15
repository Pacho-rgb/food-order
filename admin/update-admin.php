<?php 
require_once __DIR__."/partials/menu.php"; 

// Accessing the id variable
$id = $_GET['id'] ?? null;

if(!$id){
    header('Location: '.SITEURL.'admin/manage-admin.php');
    exit;
}
// Populating the input fields with the values from the database which we want to update
$statement = $pdo->prepare("
    SELECT * FROM admins WHERE id=:id
");
$statement->bindValue(':id', $id);
$statement->execute();
$admin = $statement->fetch(PDO::FETCH_ASSOC);

// If fetch statement returns a FALSE due to lack of data, redirect to manage-admin file and end file execution
if(!$admin){
    header('Location: '.SITEURL.'admin/manage-admin.php');
    exit;
}

$full_name = $admin['full_name'];
$username = $admin['username'];

?>

<div class="main-content">
        <div class="wrapper">
            <h1>Update admin</h1><br>

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
                            <td><input type="text" name="full_name" value="<?php echo $full_name  ?>" placeholder="Enter your name"></td>
                        </tr>
                        <tr>
                            <td>Username: </td>
                            <td><input type="text" name="username" value="<?php echo $username ?>" placeholder="Enter your username"></td>
                        </tr>
                        <!-- <tr>
                            <td>Password: </td>
                            <td><input type="password" name="password" placeholder="Enter your password"></td>
                        </tr> -->
                        <tr>
                            <td colspan="2"><input type="submit" name="submit" value="Update Admin" class="btn-secondary"></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Harvesting the data form the $_POST supergoabal
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];

    // echo '<pre>';
    // var_dump($id);
    // echo '</pre>';
    // exit;

    // Creating a database query
    // Note that there is no single validation we have applied
    $statement = $pdo->prepare("
        UPDATE admins SET full_name=:full_name, username=:username
        WHERE id=:id
    ");
    $statement->bindValue(':full_name', $full_name); 
    $statement->bindValue(':username', $username);
    $statement->bindValue(':id', $id);
    if ($statement->execute()){
        // Create a session variable to display message
        $_SESSION['update'] = '<div class="flash-success">Admin updated successfully</div>';
        // Redirect to the manage-admin page
        header("Location: ".SITEURL."admin/manage-admin.php");
    }else{
        // Create a session variable to display message
        $_SESSION['update'] = '<div class="flash-error">Failed to update admin</div>';
        // Redirect to the update-admin page
        header("Location: ".SITEURL."admin/update-admin.php");
    }
}

?>

<?php require_once __DIR__."/partials/footer.php"; ?>