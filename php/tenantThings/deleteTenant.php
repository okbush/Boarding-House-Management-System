<?php
include '../mysql.php';

$TenantID = $_POST['TenantID'];

$sql = "DELETE FROM tenant WHERE TenantID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $TenantID);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../tenantTable.php");
if ($stmt->affected_rows > 0) {
    header('Location: ../tenantTable.php' . '&success=' . urlencode('Tenant deleted successfully'));
} else {
    header('Location: ../tenantTable.php' . '&error=' . urlencode('Failed to delete tenant. Please try again.'));
}
exit;
?>
