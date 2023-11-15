<?php 
require_once __DIR__.'/partials/menu.php';
require_once __DIR__.'/randomString.php'; 

// Accessing the id variable
$id = $_GET['id'] ?? null;

if(!$id){
    header('Location: '.SITEURL.'admin/manage-category.php');
    exit;
}
// Populating the input fields with the values from the database which we want to update
$statement = $pdo->prepare("
    SELECT * FROM categories WHERE id=:id
");
$statement->bindValue(':id', $id);
$statement->execute();
$category = $statement->fetch(PDO::FETCH_ASSOC);

// If fetch statement returns a FALSE due to lack of data, redirect to manage-admin file and end file execution
if(!$category){
    $_SESSION['no-category-found'] = '<div class="flash-error">No such category</div>';
    header('Location: '.SITEURL.'admin/manage-category.php');
    exit;
}

$title = $category['title'];
$image = $category["image_name"];
$featured = $category["featured"];
$active = $category["active"];

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1><br><br>
        <?php
        echo $_SESSION['update-category'] ?? '';
        unset($_SESSION['update-category']);

        echo $_SESSION['upload'] ?? '';
        unset($_SESSION['upload']);
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td><input type="text" name="title" value="<?php echo $title  ?>" placeholder="Category title"></td>
                </tr>
                <!-- Image to be displayed here -->
                <tr>
                    <td>Current Image: </td>
                    <td><img style="width: 80px;" src="../images/category/<?php echo $image  ?>" alt="category_image"></td>
                </tr>
                <!-- Input of the updated image -->
                <tr>
                    <td>Image: </td>
                    <td><input type="file" name="image_name"></td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if ($featured == 'Yes'){echo "checked";}  ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if ($featured == 'No'){echo "checked";}  ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if ($active == 'Yes'){echo "checked";}  ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if ($active == 'No'){echo "checked";}  ?> type="radio" name="active" value="No">No
                    </td>
                </tr>

            </table>
            <tr>
                <td colspan="2">
                    <button class="btn-primary" type="submit">Submit</button>
                </td>
            </tr>
        </form>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Harvesting the data form the $_POST supergoabal
    $title = $_POST['title'];
    $featured = $_POST['featured'];
    $active = $_POST['active'];

    // Dealing with the file system
    $image_name = $_FILES["image_name"]["name"];
    if ($image_name){
        // If there exists an uploaded image file
        // Autoremaing the image so that it cannot be overridden when the same image name is uploaded
        // Getting the current image extension
        $extension = substr($image_name, strpos($image_name, '.'));
        $image_name = 'Food_category_'.randomString(8).$extension;

        $source_path = $_FILES["image_name"]["tmp_name"];
        $destination_path = "../images/category/$image_name";
        // Move the uploaded file
        $upload = move_uploaded_file($source_path, $destination_path);
        if($upload === false){
            $_SESSION['upload'] = '<div class="flash-error">Failed to upload image</div>';
            header('Location: '.SITEURL.'admin/update-category.php');
            exit;
        }
        // Delete the pre-existing image from our folder of storage
        unlink(__DIR__."/../images/category/$image");
    }else{
        // If the image is not uploaded
        // We take the original name of the uploaded file and assign it to the $image_name variable
        $image_name = $image;
    }

    // Creating a database query
    // Note that there is no single validation we have applied
    $statement = $pdo->prepare("
        UPDATE categories SET title=:title, featured=:featured, active=:active, image_name=:image_name
        WHERE id=:id
    ");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':featured', $featured);
    $statement->bindValue(':active', $active);
    $statement->bindValue(':image_name', $image_name);
    $statement->bindValue(':id', $id);
    if ($statement->execute()){
        // Create a session variable to display message
        $_SESSION['update-category'] = '<div class="flash-success">Category updated successfully</div>';
        // Redirect to the manage-category page
        header("Location: ".SITEURL."admin/manage-category.php");
    }else{
        // Create a session variable to display message
        $_SESSION['update-category'] = '<div class="flash-error">Failed to update category</div>';
        // Redirect to the update-category page
        header("Location: ".SITEURL."admin/update-category.php");
    }
}

?>

<?php require_once __DIR__.'/partials/footer.php'; ?>
