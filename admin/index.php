<?php require_once __DIR__."/partials/menu.php"; ?>

    <!-- Main content -->
    <div class="main-content">
        <div class="wrapper">
            <?php
            echo $_SESSION['login'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['login']);
            ?>
            <h1>Dashboard</h1>
            <div class="col-4 text-center">
                <?php
                // Creating query to connect to the database
                $statement = $pdo->prepare("
                SELECT * FROM categories
                ");
                $statement->execute();
                $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
                $categories = count($categories)
                ?>
                <h1><?php echo $categories; ?></h1><br>
                Categories
            </div>
            <div class="col-4 text-center">
                <?php
                // Creating query to connect to the database
                $statement = $pdo->prepare("
                SELECT * FROM foods
                ");
                $statement->execute();
                $foods = $statement->fetchAll(PDO::FETCH_ASSOC);
                $foods = count($foods)
                ?>
                <h1><?php echo $foods; ?></h1><br>
                Foods
            </div>
            <div class="col-4 text-center">
                <?php
                // Creating query to connect to the database
                $statement = $pdo->prepare("
                SELECT * FROM orders
                ");
                $statement->execute();
                $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
                $orders = count($orders)
                ?>
                <h1><?php echo $orders; ?></h1><br>
                Total Orders
            </div>
            <div class="col-4 text-center">
                <?php
                // Creating query to connect to the database
                $statement = $pdo->prepare("
                SELECT total FROM orders WHERE status='delivered'
                ");
                $statement->execute();
                $total = $statement->fetchAll(PDO::FETCH_COLUMN);
                $total = array_sum($total);
                ?>
                <h1><?php echo '$'.$total; ?></h1><br>
                Income Generated
            </div>
            <div class="clearfix">

            </div>
        </div>
    </div>
    <!-- End of main content -->
<?php require_once __DIR__."/partials/footer.php"; ?>

