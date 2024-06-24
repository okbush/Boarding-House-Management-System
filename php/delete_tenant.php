<?php
include 'mysql.php';

if (isset($_GET['id'])) {
    $TenantID = $_GET['id'];

    $sql = "DELETE FROM Tenant WHERE TenantID=$TenantID";
    
    if ($conn->query($sql) === TRUE) {
        echo "Employee deleted successfully";
    } else {    
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: index1.php");
    exit();
}
?>
