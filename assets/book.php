<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('database.php');

if (isset($_GET['tour_id'])) {
    $tourId = $_GET['tour_id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Tours WHERE id = ?");
        $stmt->execute([$tourId]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tour) {
            echo 'Tour not found.';
            die();
        }
    } catch (PDOException $e) {
        echo 'Database query error: ' . $e->getMessage();
        die();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM Booking WHERE userId = ? AND tourId = ?");
        $stmt->execute([$_SESSION['user_id'], $tour_id]);
        $existingBooking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingBooking) {
            echo 'You have already booked this tour.';
            exit();
        }
    } catch (PDOException $e) {
        echo 'Database query error: ' . $e->getMessage();
        die();
    }

    $availableCapacity = $tour['capacity'] ;

    if ($availableCapacity > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $travelers = $_POST['travelers'];

            try {
                $pdo->beginTransaction();


                if ($availableCapacity < $travelers) {
                    echo 'Travellers cannot be greater than available capacity';
                    die();
                }


                $stmt = $pdo->prepare("UPDATE Tours SET capacity = capacity - 1 WHERE tourId = ?");
                $stmt->execute([$tour_id]);

                $stmt = $pdo->prepare("INSERT INTO Booking (userId, tourId,travelers) VALUES (?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $tour_id, $travelers]);

                $pdo->commit();

                echo 'Booking successful!';
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo 'Booking failed. Error: ' . $e->getMessage();
            }
        }
    } else {
        echo 'No available capacity for this tour.';
    }

} else {
    echo 'Tour ID not provided.';
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Tour</title>
</head>

<body>
    <h2>Book Tour</h2>

    <table>
        <thead>
            <tr>
                <th>Location</th>
                <th>Available Capacity</th>
                <th>Date</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $tour['location']; ?></td>
                <td><?php echo $tour['capacity']; ?></td>
                <td><?php echo $tour['date']; ?></td>
                <td><?php echo $tour['price']; ?></td>
                <td>
                    <?php if ($availableCapacity > 0) : ?>
                        <form method="post" action="" onsubmit="return validateForm()" >
                            <input type="text" name="travellers" placeholder="Enter No of Travellers" required>
                            <input type="submit" value="Book Now">
                        </form>
                    <?php else : ?>
                        <p>No available capacity for this tour.</p>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

</body>
<script>


    function validateForm() {
        var travellers = document.getElementById('travellers').value;
        var availableCapacity = document.getElementById('availableCapacity').value;

        if (travellers > availableCapacity) {
            alert("Travellers cannot be greater than available capacity");
            return false;
        }
    }


</script>

</html>
