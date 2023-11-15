<?php require_once __DIR__."/partials/menu.php"; ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Order</h1><br>
        <?php
        echo $_SESSION['update-order'] ?? '';
        unset($_SESSION['update-order']);
        ?>

            <!-- Creating the sql query to retrieve data, in order to view  -->
            <?php
                $statement = $pdo->prepare("
                    SELECT * FROM orders JOIN foods ON orders.food_id = foods.id ORDER BY order_date;
                ");
                $statement->execute();
                $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
                // echo '<pre>';
                // var_dump($orders);
                // echo '</pre>';
                // exit;
            ?>
                <table class="tbl-full">

                    <thead>
                        <tr>
                            <th scope="col">S.N</th> 
                            <th scope="col">Title</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Name</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Email</th>
                            <th scope="col">Address</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($orders as $i => $order):  ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $order['title']; ?></td>
                            <td><?php echo $order['price']; ?></td>
                            <td style="margin-left: 3px;"><?php echo $order['quantity']; ?></td>
                            <td><?php echo $order['total']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['customer_name']; ?></td>
                            <td><?php echo $order['customer_contact']; ?></td>
                            <td><?php echo $order['customer_email']; ?></td>
                            <td><?php echo $order['customer_address']; ?></td>
                            <td>
                                <a href="update-order.php?id=<?php echo $order['id'] ?>" class="btn-secondary">Update Order</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
    </div>
</div>

<?php require_once __DIR__."/partials/footer.php"; ?>