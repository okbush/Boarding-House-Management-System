<?php
include '../mysql.php';

$occupancyID = $_POST['OccupancyID'];
$quantity = $_POST['Quantity'];

$sql = "INSERT INTO appliances (OccupancyID, Quantity) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $occupancyID, $quantity);

if ($stmt->execute()) {
    header('Location: ../tenants.php?error=Failed to add appliance');
    exit();
} else {
    header('Location: ../tenants.php?error=Failed to add appliance');
    exit();
}

$stmt->close();
$conn->close();
?>
