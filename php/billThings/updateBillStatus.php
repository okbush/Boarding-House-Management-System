<?php
include '../mysql.php';

$BillRefNo = $_POST['BillRefNo'];

$sql = "UPDATE billing SET BillStatus = 'Paid' WHERE BillRefNo = $BillRefNo";

if ($conn->query($sql) === TRUE) {
    header("Location: ../billing.php?success=true");
    exit();
} else {
    header("Location: ../billing.php?error=true");
    exit();
}

$conn->close();
?>
