<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"]) && isset($_SESSION["cart"])) {
    $user_id = $_SESSION["user_id"];

    try {

        $pdo->beginTransaction();

        foreach ($_SESSION["cart"] as $item) {
            $tour_id = $item["id"];
            $travelers = $item["travelers"];


            $stmt_book = $pdo->prepare("INSERT INTO booking (userId, tourId, travellers) VALUES (?, ?, ?)");
            $stmt_book->execute([$user_id, $tour_id, $travelers]);


            $stmt_update_capacity = $pdo->prepare("UPDATE tours SET capacity = capacity - ? WHERE id = ?");
            $stmt_update_capacity->execute([$travelers, $tour_id]);
        }


        $pdo->commit();


        unset($_SESSION["cart"]);

        echo "Checkout successful!";



        echo "<br>Redirecting to dashboard in 2 seconds...  ";

        header("refresh:2;url=dashboard.php");
        exit();

    } catch (PDOException $e) {

        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
        echo "<br>Redirecting to dashboard in 2 seconds...  ";

        header("refresh:2;url=dashboard.php");
        exit();
    }
}
?>
