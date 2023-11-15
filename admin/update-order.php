<?php require_once __DIR__."/partials/menu.php"; ?>
<!-- In this case, we're only going to update the quantity and the order status -->
<!-- Creating the sql query to autofill the input from the database -->
<?php

// Harvesting the id
$id = $_GET['id'] ?? '';
if(!$id){
    header('Location: '.SITEURL.'admin/manage-order.php');
    exit;
}

$statement = $pdo->prepare("
    SELECT * FROM orders where id=:id
");
$statement->bindValue(':id', $id);
$statement->execute();
$order = $statement->fetch(PDO::FETCH_ASSOC);

// Harvesting the attributes from the table row
$food_name = $order['food'];
$price = $order['price'];
$quantity = $order['quantity'];
$status = $order['status'];
$customer_name = $order['customer_name'];
$customer_contact = $order['customer_contact'];
$customer_email = $order['customer_email'];
$customer_address = $order['customer_address'];
?>

<div class="main-content">
    <div class="wrapper">
        <h2>Update Order</h2><br>
        <form action="" method="post">
            <table class="tbl-30">
                <tr>
                    <td>Food Name: </td>
                    <td><h5><?php echo $food_name ?></h5></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><h5><?php echo $price ?></h5></td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td><input type="number" name="quantity" value="<?php echo $quantity ?>"></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><select name="status" id="">
                        <option <?php if($status == 'ordered'){echo 'selected';} ?> value="ordered">Ordered</option>
                        <option <?php if($status == 'on-delivery'){echo 'selected';} ?> value="on-delivery">On Delivery</option>
                        <option <?php if($status == 'delivered'){echo 'selected';} ?> value="delivered">Delivered</option>
                        <option <?php if($status == 'cancel'){echo 'selected';} ?> value="cancel">Cancelled</option>
                    </select></td>
                </tr>
                <tr>
                    <td>Customer Name:</td>
                    <td><input type="text" name="customer_name" value="<?php echo $customer_name ?>"></td>
                </tr>
                <tr>
                    <td>Customer Contact:</td>
                    <td><input type="tel" name="customer_contact" value="<?php echo $customer_contact ?>"></td>
                </tr>
                <tr>
                    <td>Customer Email:</td>
                    <td><input type="email" name="customer_email" value="<?php echo $customer_email ?>"></td>
                </tr>
                <tr>
                    <td>Customer Address:</td>
                    <td><input type="tet" name="customer_address" value="<?php echo $customer_address ?>"></td>
                </tr>
            </table>
            <tr>
                <button type="submit" class="btn-primary">Submit</button>
            </tr>
        </form>
    </div>
</div>
<!-- Dealing with the post data -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Harvest the post data
    $quantity = $_POST['quantity'];
    $total = $price * $quantity;
    $status = $_POST['status'];
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $customer_email = $_POST['customer_email'];
    $customer_address = $_POST['customer_address'];

    // Creating an sql query to update the database
    $statement = $pdo->prepare("
    UPDATE orders SET
    quantity=:quantity,
    total=:total,
    status=:status,
    customer_name=:customer_name,
    customer_contact=:customer_contact,
    customer_email=:customer_email,
    customer_address=:customer_address
    WHERE id=:id
    ");

    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':total', $total);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':customer_name', $customer_name);
    $statement->bindValue(':customer_contact', $customer_contact);
    $statement->bindValue(':customer_email', $customer_email);
    $statement->bindValue(':customer_address', $customer_address);
    $statement->bindValue(':id', $id);

    if($statement->execute()){
        $_SESSION['update-order'] = '<div class="flash-success">Order updated successfully</div>';
        header("Location: ".SITEURL."admin/manage-order.php");
    }else{
        $_SESSION['update-order'] = '<div class="flash-error">Failed to update order</div>';
        header("Location: ".SITEURL."admin/update-order.php");
    }

}
?>

<?php require_once __DIR__."/partials/footer.php"; ?>
