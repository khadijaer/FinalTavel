<?php
session_start();
include("database.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

try {
    $stmt_user = $pdo->prepare("SELECT  email, contactNo FROM user WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user_details = $stmt_user->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user details: " . $e->getMessage();
}


$stmt_tours = $pdo->prepare("SELECT * FROM tours");
$stmt_tours->execute();
$result_tours = $stmt_tours->fetchAll();


$cartIsEmpty = !isset($_SESSION["cart"]) || empty($_SESSION["cart"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            border: 3px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #73b9ff;
        }

        form {
            display: inline;
        }
        .topnav {
        overflow: hidden;
        background-color: aquamarine;
        }

        .topnav a {
        float: left;
        color: blueviolet;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        }

        .topnav a:hover {
        background-color: lightblue;
        color: black;
        }

        .topnav a.active {
        background-color: rgb(93,9,238);
        color: white;
        }


    </style>
</head>
<body>
<div class="topnav">
  <a class="active" href="#home">Dashboard</a>
  <a href="order_history.php">Order History</a>
  <a href="update_profile.php">Update Profile</a>
  <a href="logout.php">Logout</a>
</div>
    <h2>Welcome to Your Dashboard, <?php echo $user_details['username']; ?>!</h2>
    <p>User Details:</p>
    <ul>
        <li><strong>Email:</strong> <?php echo $user_details['email']; ?></li>
        <li><strong>Phone:</strong> <?php echo $user_details['contactNo']; ?></li>
    </ul>

    <h3>Available Tours</h3>
    <table>
        <thead>
            <tr>
                <th>Tour Location</th>
                <th>Tour Date</th>
                <th>Capacity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result_tours as $row): ?>
                <tr>
                    <td><?php echo $row["location"]; ?></td>
                    <td><?php echo $row["date"]; ?></td>
                    <td><?php echo $row["capacity"]; ?></td>
                    <td>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="tour_id" value="<?php echo $row["id"]; ?>">
                            <label for="travelers">Travelers:</label>
                            <input type="number" id="travelers" name="travelers" min="1" required>
                            <button type="submit">Add to Cart</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php if (!$cartIsEmpty): ?>
        <a href="cart.php">View Cart</a><br>
    <?php endif; ?>

</body>
</html>
