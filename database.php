<?php

try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=FastTravel', 'root', 'mysql');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    echo 'Connection failed: ' . $e->getMessage();
    die();
}

?>
