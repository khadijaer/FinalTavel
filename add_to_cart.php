<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $tour_id = $_POST["tour_id"];
    $travelers = $_POST["travelers"];


    try {
        $stmt_capacity = $pdo->prepare("SELECT capacity FROM tours WHERE id = ?");
        $stmt_capacity->execute([$tour_id]);
        $capacity = $stmt_capacity->fetchColumn();
        $stmt_tour = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
        $stmt_tour->execute([$tour_id]);
        $tour = $stmt_tour->fetch(PDO::FETCH_ASSOC);

        if ($travelers <= $capacity) {
            $_SESSION["cart"][] = [
                "id" => $tour_id,
                "travelers" => $travelers,
                "location" => $tour["location"],
                "price" => $tour["price"],
                "date" => $tour["date"]
            ];
            echo "Added to cart successfully!";
            echo "<br>Redirecting to dashboard in 2 seconds...  ";
            echo "<br><a href='cart.php'>View Cart</a>";
            header("refresh:2;url=dashboard.php");
            exit();
        } else {
            echo "Cannot add more travelers than available capacity.";
            header ("refresh:2;url=dashboard.php");
            echo "<br><a href='cart.php'>View Cart</a>";
            header("refresh:2;url=dashboard.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        echo "<br><a href='cart.php'>View Cart</a>";
            header("refresh:2;url=dashboard.php");
            exit();
    }
    
}
