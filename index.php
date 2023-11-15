<?php require_once __DIR__.'/partials-front/menu.php' ;?>

<?php
// if (!$_SESSION['authorized']){
//     header('Location: '.SITEURL.'login.php');
//     exit;
// }
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

    <?php
    echo $_SESSION['order'] ?? '';
    unset($_SESSION['order']);

    echo $_SESSION['user-register'] ?? '';
    unset($_SESSION['user-register']);

    echo $_SESSION['user-login'] ?? '';
    unset($_SESSION['user-login']);
    ?>

    <!-- CAtegories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <!-- Getting all of the categories from the database which are both active and featured, then displaying them right here -->
            <?php
            $statement = $pdo->prepare("
            SELECT * FROM categories WHERE featured='Yes' AND active='Yes' ORDER BY id DESC LIMIT 3
            ");
            $statement->execute();
            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Displaying the categories which are both active and featured right here -->
            <?php foreach($categories as $i => $category) : ?>
                    <a href=<?php echo SITEURL."category-foods.php?category_id=$category[id]&category_title=$category[title]"; ?>>
                    <div class="box-3 float-container">
                        <img src="<?php echo SITEURL.'/images/category/'.$category['image_name']; ?>" alt="Pizza" class="img-responsive img-curve">
                        <h3 class="float-text text-white"><?php echo $category['title']; ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>

            <div class="clearfix"></div>
        </div>
    </section> 
    <!-- Categories Section Ends Here -->

    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <!-- Here, we'll work in displaying the foods which are both active and featured from the database -->
            <?php
            // Getting the values from the database and displaying them
            $statement = $pdo->prepare("
            SELECT * FROM foods WHERE active='Yes' and featured='Yes' ORDER BY id DESC LIMIT 6
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

        <p class="text-center">
            <a href="#">See All Foods</a>
        </p>
    </section>
    <!-- fOOD Menu Section Ends Here -->

<?php require_once __DIR__.'/partials-front/footer.php' ?>    