<?php
include '../mysql.php';

$TenantID = $_POST['TenantID'];
$TenFname = $_POST['firstname'];
$TenMname = $_POST['middlename'];
$TenLname = $_POST['lastname'];
$TenEmail = $_POST['email'];
$TenConNum = $_POST['phone'];
$TenHouseNum = $_POST['housenum'];
$TenSt = $_POST['street'];
$TenBarangay = $_POST['barangay'];
$TenCity = $_POST['city'];
$TenProv = $_POST['province'];
$TenGender = $_POST['gender'];
$TenBdate = $_POST['birthday'];
$EmConFname = $_POST['emconfirstname'];
$EmConMname = $_POST['emconmiddlename'];
$EmConLname = $_POST['emconlastname'];
$EmConNum = $_POST['emconphone'];

$sql = "UPDATE tenant SET TenFname = ?, TenMname = ?, TenLname = ?, TenEmail = ?, TenConNum = ?, TenHouseNum = ?, TenSt = ?, TenBarangay = ?, TenCity = ?, TenProv = ?, TenGender = ?, TenBdate = ?, EmConFname = ?, EmConMname = ?, EmConLname = ?, EmConNum = ? WHERE TenantID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssissssssssssi", $TenFname, $TenMname, $TenLname, $TenEmail, $TenConNum, $TenHouseNum, $TenSt, $TenBarangay, $TenCity, $TenProv, $TenGender, $TenBdate, $EmConFname, $EmConMname, $EmConLname, $EmConNum, $TenantID);

if ($stmt->execute()) {
    header("Location: tenantinfo.php?TenantID=$TenantID&success=Profile updated successfully");
} else {
    header("Location: tenantinfo.php?TenantID=$TenantID&error=Profile update failed");
}

$stmt->close();
$conn->close();
?>
