<?php
session_start();
include("database.php");


if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "ADMIN") {
    header("Location: unauthorized.php");
    exit();
}


function getAllTours() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM tours");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}


function getTourById($tourId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
        $stmt->execute([$tourId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function modifyTour($tourId, $newDate, $newCapacity) {
    global $pdo;
    try {
        $updateFields = [];
        $updateValues = [];

        if (!empty($newDate)) {
            $updateFields[] = "date = ?";
            $updateValues[] = $newDate;
        }

        if (!empty($newCapacity)) {
            $updateFields[] = "capacity = ?";
            $updateValues[] = $newCapacity;
        }

        if (empty($updateFields)) {
            return false;
        }

        $updateFieldsStr = implode(", ", $updateFields);
        $stmt = $pdo->prepare("UPDATE tours SET $updateFieldsStr WHERE id = ?");
        $updateValues[] = $tourId;
        $stmt->execute($updateValues);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["modifyTour"])) {
        $tourId = $_POST["tourId"];
        $newDate = $_POST["newDate"];
        $newCapacity = $_POST["newCapacity"];

        if (modifyTour($tourId, $newDate, $newCapacity)) {
            $modifyTourSuccess = "Tour modified successfully!";
        } else {
            $modifyTourError = "Error modifying tour. Please try again.";
        }
    } elseif (isset($_POST["addTour"])) {
        $location = $_POST["location"];
        $date = $_POST["date"];
        $capacity = $_POST["capacity"];

        try {
            $stmt = $pdo->prepare("INSERT INTO tours (location, date, capacity) VALUES (?, ?, ?)");
            $stmt->execute([$location, $date, $capacity]);
            $addTourSuccess = "Tour added successfully!";
        } catch (PDOException $e) {
            $addTourError = "Error adding tour. Please try again.";
        }
    }
}

$tours = getAllTours();
?>

<!DOCTYPE html>
<html lang="en">
<head>

</head>
<style>
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

<body>
    <div class="dashboard">
        <h2>Welcome to the Admin Dashboard</h2>

        <h3>Modify Tour</h3>
        <form action="admin_dashboard.php" method="post">
            <label for="tourId">Select Tour:</label>
            <select id="tourId" name="tourId" required>
                <?php foreach ($tours as $index => $tour): ?>
                    <option value="<?php echo $tour['id']; ?>"><?php echo $index + 1; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="newDate">New Date:</label>
            <input type="date" id="newDate" name="newDate">

            <label for="newCapacity">New Capacity:</label>
            <input type="number" id="newCapacity" name="newCapacity">

            <button type="submit" name="modifyTour">Modify Tour</button>
        </form>

        <?php if (isset($modifyTourSuccess)): ?>
            <p class="success"><?php echo $modifyTourSuccess; ?></p>
        <?php endif; ?>

        <?php if (isset($modifyTourError)): ?>
            <p class="error"><?php echo $modifyTourError; ?></p>
        <?php endif; ?>

        <h3>Add New Tour</h3>
        <form action="admin_dashboard.php" method="post">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>

            <button type="submit" name="addTour">Add Tour</button>
        </form>

        <?php if (isset($addTourSuccess)): ?>
            <p class="success"><?php echo $addTourSuccess; ?></p>
        <?php endif; ?>

        <?php if (isset($addTourError)): ?>
            <p class="error"><?php echo $addTourError; ?></p>
        <?php endif; ?>

        <h3>List of Tours</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Capacity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tours as $index => $tour): ?>
                    <tr>
                        <td><?php echo $tour['id']; ?></td>
                        <td><?php echo $tour['location']; ?></td>
                        <td><?php echo $tour['date']; ?></td>
                        <td><?php echo $tour['capacity']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
