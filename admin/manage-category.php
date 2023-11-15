<?php


 require_once __DIR__."/partials/menu.php"; ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Category</h1><br>

        <?php
        // Displaying the feedback messages
        echo $_SESSION['add-category'] ?? '';
        unset($_SESSION['add-category']);

        echo $_SESSION['delete-category'] ?? '';
        unset($_SESSION['delete-category']);

        echo $_SESSION['no-category-found'] ?? '';
        unset($_SESSION['no-category-found']);

        echo $_SESSION['update-category'] ?? '';
        unset($_SESSION['update-category']);

        // Making the connections to the database to read the data
        $statement = $pdo->prepare("
            SELECT * FROM categories ORDER BY id DESC
        ");
        $statement->execute();
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <br>

        <a href="add-category.php" class="btn-primary">Add Category</a>
            <br>

                <table class="tbl-full">
                    <thead>
                        <tr>
                            <th scope="row">S.N</th>
                            <th scope="row">Title</th>
                            <th scope="row">Image</th>
                            <th scope="row">Featured</th>
                            <th scope="row">Active</th>
                            <th scope="row">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $i => $category) :?>
                            <tr>
                            <td><?php echo $i + 1 ?></td>
                            <td><?php echo $category['title'] ?></td>
                            <td><img style="width: 80px;" alt="category_image" src="<?php echo '../images/category/'. $category['image_name'] ?>"></td>
                            <td><?php echo $category['featured'] ?></td>
                            <td><?php echo $category['active'] ?></td>
                            <td>
                                <a href="update-category.php?id=<?php echo $category['id'] ?>" class="btn-secondary">Update Category</a>
                                <form method="post" action="delete-category.php">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                    <input type="hidden" name="image_name" value="<?php echo $category['image_name']; ?>">
                                    <button type="submit" class="btn-danger">Delete Category</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

    </div>
</div>

<?php require_once __DIR__."/partials/footer.php"; ?>