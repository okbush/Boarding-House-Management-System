<?php
include '../mysql.php';

$BillRefNo = $_POST['BillRefNo'];

$query = "DELETE FROM billing WHERE BillRefNo = '$BillRefNo'";
$result = mysqli_query($conn, $query);

if ($result) {
    header("Location: ../billing.php?success=true");
    exit;
} else {
    header("Location: ../billing.php?error=true");
    exit;
}

mysqli_close($conn);
?>
