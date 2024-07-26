<?php
include '../mysql.php';

$OccupancyID = $_POST['OccupancyID'];

$stmtOccupancy = $conn->prepare("SELECT OccDateStart, OccDateEnd, Occtype FROM occupancy WHERE OccupancyID = ?");
$stmtOccupancy->bind_param("i", $OccupancyID);
$stmtOccupancy->execute();
$result = $stmtOccupancy->get_result();
$row = $result->fetch_assoc();

$OccDateStart = $row['OccDateStart'];
$OccDateEnd = $row['OccDateEnd'];
$OccType = $row['Occtype'];

$stmt = $conn->prepare("SELECT OccRate FROM occtype WHERE Occtype = ?");
$stmt->bind_param("s", $OccType);
$stmt->execute();
$stmt->bind_result($OccRate);
$stmt->fetch();
$stmt->close();

$stmtPreviousBill = $conn->prepare("SELECT BillDueDate FROM billing WHERE OccupancyID = ? ORDER BY BillRefNo DESC LIMIT 1");
$stmtPreviousBill->bind_param("i", $OccupancyID);
$stmtPreviousBill->execute();
$resultPreviousBill = $stmtPreviousBill->get_result();
$rowPreviousBill = $resultPreviousBill->fetch_assoc();

$previousBillDueDate = $rowPreviousBill['BillDueDate'];

$Status = 'Unpaid';
$Rtype = 'Rent';
$BillDateIssued = date('Y-m-d');
$BillDueDate = date('Y-m-d', strtotime('+1 month', strtotime($previousBillDueDate)));

$stmtRent = $conn->prepare("INSERT INTO billing (OccupancyID, OccRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmtRent->bind_param("iisssiss", $OccupancyID, $OccRate, $BillDateIssued, $BillDueDate, $BillDueDate, $OccRate, $Status, $Rtype);
$stmtRent->execute();

if ($result) {
    header("Location: ../billing.php?success=true");
    exit;
} else {
    header("Location: ../billing.php?error=true");
    exit;
}

?>
