<?php

require_once __DIR__."/partials/menu.php";
 
// We'll delete a particular admin based on the id
// Remember that this id will be from a POST request
$id = $_POST['id'] ?? null;
if (!$id){
    header('Location: http://localhost/food-order-copy/admin/manage-category.php');
    exit;
} 
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// exit;
 
// Delete the image from our folder of storage
$image_name = $_POST["image_name"] ?? '';
if ($image_name){
    unlink(__DIR__."/../images/category/$image_name");
}

// Making an sql delete query
$statement = $pdo->prepare("
    DELETE FROM categories WHERE id=:id
");
$statement->bindValue(':id', $id);
// If the query is executed successufully, then we should create a session to store value displaying the success 
if ($statement->execute()){
    $_SESSION['delete-category'] = '<div class="flash-success">Category deleted successfully</div>';   
}else{
    $_SESSION['delete-category'] = '<div class="flash-error">Failed to delete category</div>';
}
header('Location: '.SITEURL.'admin/manage-category.php');
?>