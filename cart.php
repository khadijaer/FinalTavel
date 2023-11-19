<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<h2>Your cart is empty</h2>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";

} else {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shopping Cart</title>
        <style>
            body{
        background-color: lightgoldenrodyellow;

            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table, th, td {
                border: 1px solid black;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            form {
                display: inline;
            }
        </style>
    </head>
    <body>
        <h2>Shopping Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Tour Location</th>
                    <th>Tour Date</th>
                    <th>Travelers</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION["cart"] as $item): ?>
                    <tr>
                        <td><?php echo $item["location"]; ?></td>
                        <td><?php echo $item["date"]; ?></td>
                        <td><?php echo $item["travelers"]; ?></td>
                        <td>
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="tour_id" value="<?php echo $item["id"]; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="checkout.php" method="post">
            <button type="submit">Proceed to Checkout</button>
        </form>

        <a href="dashboard.php">Back to Dashboard</a>
    </body>
    </html>

    <?php
}
?>
