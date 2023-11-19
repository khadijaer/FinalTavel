<?php
session_start();
include("database.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];


try {
    $stmt_user = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user_details = $stmt_user->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user details: " . $e->getMessage();
}

$update_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST["new_email"];
    $new_phone = $_POST["new_phone"];

    try {

        $stmt_update = $pdo->prepare("UPDATE user SET email = ?, contactNo = ? WHERE id = ?");
        $stmt_update->execute([$new_email, $new_phone, $user_id]);


        $_SESSION["user_email"] = $new_email;

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $update_error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
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
    <h2>Update Profile</h2>

    <?php if ($update_error !== ""): ?>
        <p class="error"><?php echo $update_error; ?></p>
    <?php endif; ?>

    <form action="update_profile.php" method="post">
        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $user_details['email']; ?>" required>

        <label for="new_phone">New Phone:</label>
        <input type="text" id="new_phone" name="new_phone" value="<?php echo $user_details['contactNo']; ?>" required>

        <button type="submit">Update Profile</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
