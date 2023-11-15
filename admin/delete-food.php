<?php

require_once __DIR__."/partials/menu.php";
 
// We'll delete a particular food based on the id
// Remember that this id will be from a POST request
$id = $_POST['id'] ?? null;
if (!$id){
    header('Location: http://localhost/food-order-copy/admin/manage-food.php');
    exit;
} 
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// exit;
 
// Delete the image from our folder of storage
$image_name = $_POST["image_name"] ?? '';
if ($image_name){
    unlink(__DIR__."/../images/food/$image_name");
}

// Making an sql delete query
$statement = $pdo->prepare("
    DELETE FROM foods WHERE id=:id
");
$statement->bindValue(':id', $id);
// If the query is executed successufully, then we should create a session to store value displaying the success 
if ($statement->execute()){
    $_SESSION['delete-food'] = '<div class="flash-success">Food deleted successfully</div>';   
}else{
    $_SESSION['delete-food'] = '<div class="flash-error">Failed to delete food</div>';
}
header('Location: '.SITEURL.'admin/manage-food.php');
?>