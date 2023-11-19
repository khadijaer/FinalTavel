<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["cart"])) {
    $tour_id = $_POST["tour_id"];


    $index = array_search($tour_id, array_column($_SESSION["cart"], 'id'));

    if ($index !== false) {

        array_splice($_SESSION["cart"], $index, 1);
        echo "Item removed from cart.";


        echo "<br>Redirecting to cart in 2 seconds...  ";
        header("refresh:2;url=cart.php");
        exit();


    } else {
        echo "Item not found in cart.";
        echo "<br>Redirecting to cart in 2 seconds...  ";
        header("refresh:2;url=cart.php");
        exit();
    }
}
?>
