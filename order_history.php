<?php
session_start();
include("database.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

try {
    $stmt_history = $pdo->prepare("SELECT b.id, b.tourId, b.travellers, t.location, t.date
                                   FROM booking b
                                   JOIN tours t ON b.tourId = t.id
                                   WHERE b.userId = ?");
    $stmt_history->execute([$user_id]);
    $booking_history = $stmt_history->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching booking history: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
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
    </style>
</head>
<body>
    <h2>Your Order History</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Tour Location</th>
                <th>Tour Date</th>
                <th>Travelers</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($booking_history as $booking): ?>
                <tr>
                    <td><?php echo $booking["id"]; ?></td>
                    <td><?php echo $booking["location"]; ?></td>
                    <td><?php echo $booking["date"]; ?></td>
                    <td><?php echo $booking["travellers"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
