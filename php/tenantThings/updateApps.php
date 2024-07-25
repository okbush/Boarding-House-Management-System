<?php
include '../mysql.php';

$TenantID = $_POST['TenantID'];
$occupancyID = $_POST['OccupancyID'];
$appID = $_POST['AppID'];
$quantity = $_POST['Quantity'];

// Check if appID exists in appliance table
$sql_check = "SELECT COUNT(*) FROM appliances WHERE TenantAppID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $appID);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count > 0) {
    // AppID exists, update quantity
    $sql = "UPDATE appliances SET Quantity = ? WHERE TenantAppID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $appID);

    if ($stmt->execute()) {
        header("Location: tenantinfo.php?TenantID=$TenantID&success=Appliances updated successfully");
    } else {
        header("Location: tenantinfo.php?TenantID=$TenantID&error=Failed to update appliances");
    }

    $stmt->close();
} else {
    // AppID does not exist, make a new entry
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
}

$conn->close();
?>