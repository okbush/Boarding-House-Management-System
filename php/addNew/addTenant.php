<?php
include '..\mysql.php';

// Sanitize and validate input
$TenFname = filter_input(INPUT_POST, 'TenFname', FILTER_SANITIZE_STRING);
$TenLname = filter_input(INPUT_POST, 'TenLname', FILTER_SANITIZE_STRING);
$TenMname = filter_input(INPUT_POST, 'TenMname', FILTER_SANITIZE_STRING);
$TenHouseNum = filter_input(INPUT_POST, 'TenHouseNum', FILTER_SANITIZE_NUMBER_INT);
$TenSt = filter_input(INPUT_POST, 'TenSt', FILTER_SANITIZE_STRING);
$TenBarangay = filter_input(INPUT_POST, 'TenBarangay', FILTER_SANITIZE_STRING);
$TenCity = filter_input(INPUT_POST, 'TenCity', FILTER_SANITIZE_STRING);
$TenProv = filter_input(INPUT_POST, 'TenProv', FILTER_SANITIZE_STRING);
$TenConNum = filter_input(INPUT_POST, 'TenConNum', FILTER_SANITIZE_NUMBER_INT);
$TenEmail = filter_input(INPUT_POST, 'TenEmail', FILTER_SANITIZE_EMAIL);
$TenBdate = filter_input(INPUT_POST, 'TenBdate', FILTER_SANITIZE_STRING);
$TenGender = filter_input(INPUT_POST, 'TenGender', FILTER_SANITIZE_STRING);
$EmConFname = filter_input(INPUT_POST, 'EmConFname', FILTER_SANITIZE_STRING);
$EmConLname = filter_input(INPUT_POST, 'EmConLname', FILTER_SANITIZE_STRING);
$EmConMname = filter_input(INPUT_POST, 'EmConMname', FILTER_SANITIZE_STRING);
$EmConNum = filter_input(INPUT_POST, 'EmConNum', FILTER_SANITIZE_NUMBER_INT);

$sql = "INSERT INTO TENANT (TenFname, TenLname, TenMname, TenHouseNum, TenSt, TenBarangay, TenCity, TenProv, TenConNum, TenEmail, TenBdate, TenGender, EmConFname, EmConLname, EmConMname, EmConNum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssssssssss', $TenFname, $TenLname, $TenMname, $TenHouseNum, $TenSt, $TenBarangay, $TenCity, $TenProv, $TenConNum, $TenEmail, $TenBdate, $TenGender, $EmConFname, $EmConLname, $EmConMname, $EmConNum);
$stmt->execute();

header('Location: ..\tenants.php');
exit;
?>