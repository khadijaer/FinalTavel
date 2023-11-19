<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = $_POST["admin_email"];
    $admin_password = $_POST["admin_password"];

    try {
        $stmt = $pdo->prepare("SELECT id, password, type FROM user WHERE email = ?");
        $stmt->execute([$admin_email]);

        if ($row = $stmt->fetch()) {
            $user_id = $row["id"];
            $hashed_password = $row["password"];
            $user_type = $row["type"];

            if ($user_type === "ADMIN" && password_verify($admin_password, $hashed_password)) {
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_type"] = $user_type;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $login_error = "Invalid credentials!";



            }
        } else {
            $login_error = "User not found!";


        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();


    }
}
?>

<!-- The rest of your HTML remains the same -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 10%;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h2>Admin Login</h2>

        <?php if (isset($login_error)) : ?>
            <p class="error"><?php echo $login_error; ?></p>

            <!-- <a href="admin_login.php">Login</a> -->

        <?php endif; ?>

        <label for="admin_email">Email:</label>
        <input type="email" id="admin_email" name="admin_email" required>

        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password" required>

        <button type="submit">Login</button>
    </form>
</body>

</html>
