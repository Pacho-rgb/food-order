<?php require_once __DIR__.'/partials-front/menu.php' ;?>

<?php
if (!$_SESSION['authorized']){
    header('Location: '.SITEURL.'login.php');
    exit;
}
?>
    <!-- fOOD SEARCH Section Starts Here -->
    <section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

            <?php
            // Getting the food id from the post request
            $food_id = $_GET['food_id'] ?? '';
            if (!$food_id){
                header('Location: '.SITEURL);
                exit;
            }
            // Getting the selected food details from an sql query
            $statement = $pdo->prepare("
            SELECT * FROM foods WHERE id=:id
            ");
            $statement->bindValue(':id', $food_id);
            $statement->execute();
            $food = $statement->fetch(PDO::FETCH_ASSOC);

            // Harvesting the key food attributes
            $title = $food['title']; 
            $price = $food['price']; 
            $image = $food['image'];
            ?>

            <form action="" method="post" class="order">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <img src="<?php echo SITEURL.'images/food/'.$image; ?>" alt="Chicken Hawaiin Pizza" class="img-responsive img-curve">
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $title; ?></h3>
                        <p class="food-price"><?php echo $price; ?></p>

                        <div class="order-label">Quantity</div>
                        <input type="number" name="quantity" class="input-responsive" value="1" required>
                        
                    </div>
 
                </fieldset>
                
                <fieldset>
                    <legend>Delivery Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name" placeholder="E.g. John Doe" class="input-responsive" required>
                    <div style="color: pink; font-size:smaller"><?php echo $errors['name_error'] ?? ''; ?></div>

                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="E.g. 254xxxxxx" class="input-responsive" required>
                    <div style="color: pink; font-size:smaller"><?php echo $errors['contact_error'] ?? ''; ?></div>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. john@mail.com" class="input-responsive" required>
                    <div style="color: pink; font-size:smaller"><?php echo $errors['email_error'] ?? ''; ?></div>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="E.g. Street" class="input-responsive" required></textarea>

                    <button type="submit" class="btn btn-primary">Confirm Order</button>
                </fieldset>

            </form>

        </div>
    </section>

    <?php
    // Getting the posted data
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $quantity = $_POST['quantity'];
        $total = $quantity * $price;
        $order_date = date('Y-m-d H:i:s');
        $status = 'ordered';
        $customer_name = $_POST['full-name'];
        $customer_contact = $_POST['contact'];
        $customer_email = $_POST['email'];
        $customer_address = $_POST['address'];

        // Validating the user input
        $errors = [];
        if (!$customer_name || strlen($customer_name) <= 5){
            $errors['name_error'] = 'Length of the name should be more than five characters';
        }
        if (!$customer_contact || strlen($customer_contact) <= 10){
            $errors['contact_error'] = 'Invalid contact details';
        }
        if (!$customer_email || strpos($customer_email, '@') == false){
            $errors['email_error'] = 'Invalid email address';
        }

        if (!empty($errors)){
            header("Location: ".SITEURL."order.php?food_id=$food_id");
            exit;
        }

        // Save the order inside the database
        $statement = $pdo->prepare("
            INSERT INTO orders (quantity, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, food_id)
            VALUES (:quantity, :total, :order_date, :status, :customer_name, :customer_contact, :customer_email, :customer_address, :id)
        ");

        $statement->bindValue(':quantity', $quantity);
        $statement->bindValue(':total', $total);
        $statement->bindValue(':order_date', $order_date);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':customer_name', $customer_name);
        $statement->bindValue(':customer_contact', $customer_contact);
        $statement->bindValue(':customer_email', $customer_email);
        $statement->bindValue(':customer_address', $customer_address);
        $statement->bindValue(':id', $food_id);

        if($statement->execute()){
            $_SESSION['order'] = '<div class="success text-center">Food ordered successfully</div>';
            header('Location: '.SITEURL);
            exit;
        }else{
            $_SESSION['order'] = '<div class="error text-center">Faield to order food</div>';
            header('Location: '.SITEURL);
            exit; 
        }
    }
    ?>

    <!-- fOOD sEARCH Section Ends Here -->
    <?php require_once __DIR__.'/partials-front/footer.php' ?> 