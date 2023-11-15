<?php require_once __DIR__.'/partials/menu.php'; ?>
<?php require_once __DIR__.'/randomString.php'; ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1><br><br>
        <?php
        echo $_SESSION['add-category'] ?? '';
        unset($_SESSION['add-category']);

        echo $_SESSION['upload'] ?? ''; 
        unset($_SESSION['upload']);
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td><input type="text" name="title" placeholder="Category title"></td>
                </tr>

                <tr>
                    <td>Image: </td>
                    <td><input type="file" name="image_name"></td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
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

<?php require_once __DIR__.'/partials/footer.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Harvesting the data from the form
    $title = $_POST['title'];

    if (isset($_POST['featured'])){
        // Get the featured data
        $featured = $_POST['featured'];
    }else{
        // Assign the default value if not clicked anywhere
        $featured = 'No';
    }

    if (isset($_POST['active'])){
        // Get the featured data
        $active = $_POST['active'];
    }else{
        // Assign the default value if not clicked anywhere
        $active = 'No';
    }
    // echo '<pre>';
    // var_dump($_FILES["image_name"]);
    // echo '</pre>';
    // exit;

    // Checking whether the image is selected/uploaded
    if($_FILES["image_name"]["tmp_name"]){
        $image_name = $_FILES["image_name"]["name"];

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
            header('Location: '.SITEURL.'admin/add-category.php');
            exit;
        }
    }else{
        // If the image is not selected
        $image_name = '';
    } 
    
    // Creating the query to insert data into the database
    $statement = $pdo->prepare("
        INSERT INTO categories (title, featured, active, image_name)
        VALUES (:title, :featured, :active, :image_name)
    ");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':featured', $featured);
    $statement->bindValue(':active', $active);
    $statement->bindValue(':image_name', $image_name);

    if ($statement->execute()){
        $_SESSION['add-category'] = '<div class="flash-success">Category added successfully</div>';
        header('Location: '.SITEURL.'admin/manage-category.php');
        exit;
    } else {
        $_SESSION['add-category'] = '<div class="flash-error">Failed to add category</div>';
        header('Location: '.SITEURL.'admin/add-category.php');
    }
    
}



 
