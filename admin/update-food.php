<?php 
require_once __DIR__.'/partials/menu.php';
require_once __DIR__.'/randomString.php'; 

// Accessing the id variable
$id = $_GET['id'] ?? null;

if(!$id){
    header('Location: '.SITEURL.'admin/manage-food.php');
    exit;
}
// Populating the input fields with the values from the database which we want to update
$statement = $pdo->prepare("
    SELECT * FROM foods WHERE id=:id
");
$statement->bindValue(':id', $id);
$statement->execute();
$food = $statement->fetch(PDO::FETCH_ASSOC);

// If fetch statement returns a FALSE due to lack of data, redirect to manage-admin file and end file execution
if(!$food){
    $_SESSION['no-food-found'] = '<div class="flash-error">No such food</div>';
    header('Location: '.SITEURL.'admin/manage-food.php');
    exit;
}

$title = $food['title'];
$description = $food['description'];
$price = $food['price'];
$image = $food["image"];
$food_category_id = $food['category_id'];
$featured = $food["featured"];
$active = $food["active"];

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1><br><br>
        <?php
        echo $_SESSION['update-food'] ?? '';
        unset($_SESSION['update-food']);

        echo $_SESSION['upload'] ?? '';
        unset($_SESSION['upload']);
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title ?>" placeholder="Enter food title">
                    </td>
                </tr><br>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" placeholder="Enter food description" id="" cols="30" rows="10"><?php echo $description ?></textarea>
                    </td>
                </tr><br>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price ?>" placeholder="Enter food price">
                    </td>
                </tr>
                <!-- Displaying the current image -->
                <tr>
                    <td>Current Image: </td>
                    <td><img style="width: 80px;" src="../images/food/<?php echo $image  ?>" alt="category_image"></td>
                </tr>

                <tr>
                    <td>Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <!-- Note that we'll get the categories  from our database -->
                <!-- Displaying of the categories will be based on their active status -->
                <?php  
                $statement = $pdo->prepare("
                SELECT * FROM categories WHERE active='Yes' 
                ");
                $statement->execute();
                $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category" >
                            <option value="">Select category</option>
                            <?php foreach($categories as $i => $category): ?>
                                <option <?php if($category['id'] == $food_category_id){echo 'selected';} ?> value="<?php echo $category['id'] ?>"><?php echo $category['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" <?php if($featured == 'Yes'){echo 'checked';} ?> name="featured" value="Yes" id="">Yes
                        <input type="radio" <?php if($featured == 'No'){echo 'checked';} ?> name="featured" value="No" id="">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" <?php if($active == 'Yes'){echo 'checked';} ?> name="active" value="Yes" id="">Yes
                        <input type="radio" <?php if($active == 'No'){echo 'checked';} ?> name="active" value="No" id="">No
                    </td>
                </tr>

            </table>
            <button class="btn-primary" type="submit">Submit</button>
        </form>
    </div>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Harvesting the data form the $_POST supergoabal
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];
    $featured = $_POST['featured'];
    $active = $_POST['active'];

    // echo '<pre>';
    // var_dump($_POST);
    // echo '<pre>';
    // exit;

    // Dealing with the file system
    $image_name = $_FILES["image"]["name"];
    if ($image_name){
        // If there exists an uploaded image file
        // Autoremaing the image so that it cannot be overridden when the same image name is uploaded
        // Getting the current image extension
        $extension = substr($image_name, strpos($image_name, '.'));
        $image_name = 'Food_category_'.randomString(8).$extension;

        $source_path = $_FILES["image"]["tmp_name"];
        $destination_path = "../images/food/$image_name";
        // Move the uploaded file
        $upload = move_uploaded_file($source_path, $destination_path);
        if($upload === false){
            $_SESSION['upload'] = '<div class="flash-error">Failed to upload image</div>';
            header('Location: '.SITEURL.'admin/update-food.php');
            exit;
        }
        // Delete the pre-existing image from our folder of storage
        unlink(__DIR__."/../images/food/$image");
    }else{
        // If the image is not uploaded
        // We take the original name of the uploaded file and assign it to the $image_name variable
        $image_name = $image;
    }

    // Creating a database query
    // Note that there is no single validation we have applied
    $statement = $pdo->prepare("
        UPDATE foods SET title=:title, description=:description, price=:price, image=:image_name, category_id=:category_id, featured=:featured, active=:active
        WHERE id=:id
    ");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':image_name', $image_name);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':featured', $featured);
    $statement->bindValue(':active', $active);
    $statement->bindValue(':id', $id);
    if ($statement->execute()){
        // Create a session variable to display message
        $_SESSION['update-food'] = '<div class="flash-success">Food updated successfully</div>';
        // Redirect to the manage-food page
        header("Location: ".SITEURL."admin/manage-food.php");
    }else{
        // Create a session variable to display message
        $_SESSION['update-food'] = '<div class="flash-error">Failed to update food</div>';
        // Redirect to the update-category page
        header("Location: ".SITEURL."admin/update-food.php");
    }
}

?>

<?php require_once __DIR__.'/partials/footer.php'; ?> 