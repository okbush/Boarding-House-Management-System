<?php
include '../mysql.php';

$newAppRate = $_POST['appRate'];
$stmt = $conn->prepare("INSERT INTO appRate (rate) VALUES (?)");
$stmt->bind_param("i", $newAppRate);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../billing.php");
exit();
?>
