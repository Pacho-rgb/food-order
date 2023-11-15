<?php require_once __DIR__.'/partials-front/menu.php' ;?>
<?php
if (!$_SESSION['authorized']){
    header('Location: '.SITEURL.'login.php');
    exit;
}
?>
    <!-- CAtegories Section Starts Here -->
    <!-- Here, we'll display all of the categories that are active -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <!-- Getting all of the categories from the database which are active, then displaying them right here -->
            <?php
            $statement = $pdo->prepare("
            SELECT * FROM categories WHERE active='Yes' ORDER BY id DESC
            ");
            $statement->execute();
            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Displaying the categories which are active right here -->
            <?php foreach($categories as $i => $category) : ?>
                <a href=<?php echo SITEURL."category-foods.php?category_id=$category[id]&category_title=$category[title]"; ?>>
                    <div class="box-3 float-container">
                        <img src="<?php echo SITEURL.'/images/category/'.$category['image_name']; ?>" alt="Pizza" class="img-responsive img-curve">
                        <h3 class="float-text text-white"><?php echo $category['image_name']; ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
            
            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->

    <?php require_once __DIR__.'/partials-front/footer.php' ?>