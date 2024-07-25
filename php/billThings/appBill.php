<?php
include '../mysql.php';

$OccupancyID = $_POST['OccupancyID'];
$stmt = $conn->prepare("SELECT Quantity FROM appliances WHERE OccupancyID =?");
$stmt->bind_param("i", $OccupancyID);
$stmt->execute();
$stmt->bind_result($quantity);
$stmt->fetch();
$stmt->close();

$stmtLas = $conn->prepare("SELECT TenantAppID FROM appliances WHERE OccupancyID = ?");
$stmtLas->bind_param("i", $OccupancyID);
$stmtLas->execute();
$stmtLas->bind_result($TenantAppID);
$stmtLas->fetch();
$stmtLas->close();

$stmtLasQ = $conn->prepare("SELECT Quantity FROM billing WHERE BillType = 'Appliances' AND OccupancyID = ? ORDER BY BillRefNo DESC LIMIT 1");
$stmtLasQ->bind_param("i", $OccupancyID);
$stmtLasQ->execute();
$stmtLasQ->bind_result($LastQ);
$stmtLasQ->fetch();
$stmtLasQ->close();

$sql = "SELECT rate FROM appRate WHERE id = (SELECT MAX(id) FROM appRate) LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$appRate = $row['rate'];

if ($quantity > $LastQ) {
    $addQuantity = $quantity - $LastQ;
} else {
    $addQuantity = 0;
}

$BillDateIssued = date('Y-m-d');
$BillDueDate = date('Y-m-d', strtotime($BillDateIssued.'+ 1 month'));
$Rtype = 'Rent';
$Atype = 'Appliances';
$Status = 'Unpaid';

$appDue = ($appRate * $quantity) + ($appRate * $addQuantity);

$stmtApp = $conn->prepare("INSERT INTO billing (OccupancyID, TenantAppID, Quantity, AddQuantity, appRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtApp->bind_param("iiiiisssdss", $OccupancyID, $TenantAppID, $quantity, $addQuantity, $appRate, $BillDateIssued, $BillDueDate, $BillDueDate, $appDue , $Status, $Atype);
$stmtApp->execute();

if ($result) {
    header("Location: ../billing.php?success=true");
    exit;
} else {
    header("Location: ../billing.php?error=true");
    exit;
}

$stmtApp->close();
$conn->close();

?>
