<?php require_once __DIR__.'/partials-front/menu.php' ;?>
<?php
if (!$_SESSION['authorized']){
    header('Location: '.SITEURL.'login.php');
    exit;
}
?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="<?php echo SITEURL.'food-search.php'; ?>" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <!-- Creating an sql query to display all of the foods which are active -->
            <?php
            // Getting the values from the database and displaying them
            $statement = $pdo->prepare("
            SELECT * FROM foods WHERE active='Yes' ORDER BY id DESC LIMIT 6
            ");
            $statement->execute();
            $foods = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>

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

                        <a href="<?php echo SITEURL.'order.php?food_id='.$food['id']; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div> 
            <?php endforeach; ?>
            <div class="clearfix"></div>
        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->
    <?php require_once __DIR__.'/partials-front/footer.php' ?> 