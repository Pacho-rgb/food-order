<?php require_once __DIR__."/partials/menu.php"; ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Food</h1><br>

        <?php

        // Displaying the feedback messages
        echo $_SESSION['add-food'] ?? '';
        unset($_SESSION['add-food']);

        echo $_SESSION['delete-food'] ?? '';
        unset($_SESSION['delete-food']);

        echo $_SESSION['update-food'] ?? '';
        unset($_SESSION['update-food']);

        // Getting the values from the database and displaying them
        $statement = $pdo->prepare("
            SELECT * FROM foods ORDER BY id DESC
        ");
        $statement->execute();
        $foods = $statement->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <br>

        <a href="add-food.php" class="btn-primary">Add food</a><br>
            <br>

                <table class="tbl-full">
                    <thead>
                        <tr>
                            <th scope="row">S.N</th>
                            <th scope="row">Title</th>
                            <th scope="row">Price</th>
                            <th scope="row">Image</th>
                            <th scope="row">Featured</th>
                            <th scope="row">Active</th>
                            <th scope="row">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($foods as $i => $food): ?>
                        <tr>
                            <td><?php echo $i+1 ?></td> 
                            <td><?php echo $food['title'] ?></td>
                            <td><?php echo $food['price'] ?></td>
                            <td><img style="width: 80px;" src="<?php echo "../images/food/".$food['image'] ?>" alt="product_image"></td>
                            <td><?php echo $food['featured'] ?></td>
                            <td><?php echo $food['active'] ?></td>
                            <td>
                            <a href="update-food.php?id=<?php echo $food['id'] ?>" class="btn-secondary">Update Food</a>
                                <form method="post" action="delete-food.php">
                                    <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                    <input type="hidden" name="image_name" value="<?php echo $food['image']; ?>">
                                    <button type="submit" class="btn-danger">Delete Food</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

    </div>
</div>

<?php require_once __DIR__."/partials/footer.php"; ?>