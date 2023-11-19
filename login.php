<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $stmt = $pdo->prepare("SELECT id, password FROM user WHERE email = ?");
        $stmt->execute([$email]);

        if ($row = $stmt->fetch()) {
            $user_id = $row["id"];
            $hashed_password = $row["password"];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $user_id;
                header("Location: dashboard.php");
            } else {
                $login_error = "Invalid password!";
            }
        } else {
            $login_error = "User not found!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightgoldenrodyellow;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
        }

        button {
            background-color: #00008B;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #006dd8;
        }

        .error {
            color: red;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <h2>Login</h2>

    <?php if (isset($login_error)): ?>
        <p class="error"><?php echo $login_error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</body>
</html>
