<?php require_once __DIR__."/partials/menu.php"; ?>
<?php require_once __DIR__.'/randomString.php'; ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add food</h1>

        <?php
        echo $_SESSION['add-food'] ?? '';
        unset($_SESSION['add-food']);

        echo $_SESSION['upload'] ?? '';
        unset($_SESSION['upload']);
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Enter food title">
                    </td>
                </tr><br>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" placeholder="Enter food description" id="" cols="30" rows="10"></textarea>
                    </td>
                </tr><br>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" placeholder="Enter food price">
                    </td>
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
                                <option value="<?php echo $category['id'] ?>"><?php echo $category['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" id="">Yes
                        <input type="radio" name="featured" value="No" id="">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" id="">Yes
                        <input type="radio" name="active" value="No" id="">No
                    </td>
                </tr>

            </table>
            <button class="btn-primary" type="submit">Submit</button>
        </form>
    </div>
</div>

<?php
// Inserting the data into the database
// Harvesting the post data
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];

    // Dealing ith the radio buttons
    if (isset($_POST['featured'])){
        $featured = $_POST['featured'];
    }else{
        $featured = 'No';
    }

    if (isset($_POST['active'])){
        $active = $_POST['active'];
    }else{
        $active = 'No';
    }
    // echo '<pre>';
    // var_dump($_FILES);
    // echo '</pre>';
    // exit;

    // Checking if the uploaded image exists
    if ($_FILES["image"]["name"]){
        // If the uploaded image exists, rename the image with random name
        $image_name = $_FILES["image"]["name"];

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
            header('Location: '.SITEURL.'admin/add-food.php');
            exit;
        }

    }else{ 
        // If the upoaded file/image doesn't exist
        $image_name = "";
    }

    // Creating the query to insert data into the database
    $statement = $pdo->prepare("
        INSERT INTO foods (title, description, price, image, category_id, featured, active)
        VALUES (:title, :description, :price, :image, :category_id, :featured, :active)
    ");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':image', $image_name);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':featured', $featured);
    $statement->bindValue(':active', $active);

    if ($statement->execute()){
        $_SESSION['add-food'] = '<div class="flash-success">Food added successfully</div>';
        header('Location: '.SITEURL.'admin/manage-food.php');
        exit;
    } else {
        $_SESSION['add-food'] = '<div class="flash-error">Failed to add food</div>';
        header('Location: '.SITEURL.'admin/add-food.php');
    }

}
?>

<?php require_once __DIR__."/partials/footer.php"; ?>
