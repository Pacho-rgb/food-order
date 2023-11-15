# food-order This is a simple food order website.
The website basically has two parts: Admin side and client side.
The project is ORIGINALLY from Vijay Thapa's Youtube account.
Major key changes made are:
>Normalized the database.
>Inserted user input validations.
>Used PDO to connect to the database, which is frankly quite easier to me than using msqli.
>Averted the dangers of SQL injection from the original project, by using PDO's bindValue method.
