<?php

require_once __DIR__."/partials/menu.php";
 
// We'll delete a particular admin based on the id
// Remember that this id will be from a POST request
$id = $_POST['id'] ?? null;
if (!$id){
    header('Location: http://localhost/food-order-copy/admin/manage-admin.php');
    exit;
}


// Making an sql delete query
$statement = $pdo->prepare("
    DELETE FROM admins WHERE id=:id
");
$statement->bindValue(':id', $id);
// If the query is executed successufully, then we should create a session to store value displaying the success 
if ($statement->execute()){
    $_SESSION['delete'] = '<div class="flash-success">Admin deleted successfully</div>';   
}else{
    $_SESSION['delete'] = '<div class="flash-error">Failed to delete admin</div>';
}
header('Location: '.SITEURL.'admin/manage-admin.php');
?>