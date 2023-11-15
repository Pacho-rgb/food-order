<?php require_once __DIR__.'/partials-front/menu.php' ;?>

<?php
if (!$_SESSION['authorized']){
    header('Location: '.SITEURL.'login.php');
    exit;
}
?>

<!-- Harvesting the get data -->
    <?php
    $category_id = $_GET['category_id'] ?? '';
    $category_title = $_GET['category_title'] ?? '';

    if(!$category_id){
        header('Location: index.php');
        exit;
    }

    // SQL qeury to get the foods based on the the id of the category
    $statement = $pdo->prepare("
    SELECT * FROM foods WHERE active='Yes' AND category_id=:category_id ORDER BY id DESC
    ");
    $statement->bindValue(':category_id', $category_id);
    $statement->execute();
    $foods = $statement->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <h2>Foods on <a href="#" class="text-white"><?php echo $category_title ?></a></h2>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php if (!$foods): ?>
                <h4 style="color:gray; text-align:center;">No foods available for this category</h4>
            <?php endif; ?>

            <?php foreach($foods as $i => $food): ?>
                <div class="food-menu-box">
                    <div class="food-menu-img">
                        <img src="<?php echo SITEURL.'images/food/'.$food['image']; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                    </div>

                    <div class="food-menu-desc">
                        <h4><?php echo $food['title']; ?></h4>
                        <p class="food-price"><?php echo $food['price']; ?></p>
                        <p class="food-detail">
                        <?php echo $food['description']; ?>
                        </p>
                        <br>

                        <a href="<?php echo SITEURL.'/order.php?food_id='.$food['id']; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div> 
            <?php endforeach; ?>

            <div class="clearfix"></div>

            

        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->

    <?php require_once __DIR__.'/partials-front/footer.php' ?> 