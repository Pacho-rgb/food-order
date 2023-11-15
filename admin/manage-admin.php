<?php require_once __DIR__."/partials/menu.php"; ?>

    <!-- Main content -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Manage admin</h1><br>

            <!-- Remember that the message will be displayed forever, but we want to display it just once. -->
            <?php

            echo $_SESSION['add'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['add']);

            echo $_SESSION['delete'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['delete']);

            echo $_SESSION['update'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['update']);

            echo $_SESSION['user-not-found'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['user-not-found']);

            echo $_SESSION['password-update'] ?? ''; 
            // We'll just unset the value of the $_SESSION['add']
            unset($_SESSION['password-update']);

            ?><br><br>
            

            <!-- Button to add the admin -->
            <a href="add-admin.php" class="btn-primary">Add Admin</a><br>
            <br>

                <table class="tbl-full">
                    <thead>
                        <tr>
                            <th scope="row">S.N</th>
                            <th scope="row">Full Name</th>
                            <th scope="row">Username</th>
                            <th scope="row">Actions</th>
                        </tr>
                    </thead>

                    <?php
                    // Now, we want to display the admins located in our database.
                    // We'll make the SQL query in order to retrieve the data.
                    $statement = $pdo->prepare(
                        "SELECT * FROM admins ORDER BY id DESC"
                    ); 
                    $statement->execute();
                    $admins = $statement->fetchAll(PDO::FETCH_ASSOC); 

                    ?>

                    <tbody>
                        <?php foreach($admins as $i => $admin): ?>
                            <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $admin['full_name']; ?></td>
                            <td><?php echo $admin['username']; ?></td>
                            <td>
                                <!-- <form method="post" action="update-password.php">
                                    <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                    <button type="submit" class="btn-primary">Update password</button>
                                </form> -->
                                <a href="update-password.php?id=<?php echo $admin['id']; ?>" class="btn-primary">Update Password</a>
                                <a href="update-admin.php?id=<?php echo $admin['id']; ?>" class="btn-secondary">Update Admin</a>
                                <form method="post" action="delete-admin.php">
                                    <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                    <button type="submit" class="btn-danger">Delete Admin</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- End of main content -->

<?php require_once __DIR__."/partials/footer.php"; ?>