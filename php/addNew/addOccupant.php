<?php
include '../mysql.php';

$TenantID = $_POST["TenantID"];
$RoomID = $_POST["RoomID"];
$Occtype = $_POST["occupancy-type"];
$EndDate = $_POST["ending-date"];

if ($Occtype == 'Sharer') {
    static $isProcessed = false;

    if (!$isProcessed) {
        // Search for roomer's end date
        $sql = "SELECT OccDateEnd
                FROM occupancy
                WHERE (RoomID = '$RoomID' AND OccStatus = 'Active' AND Occtype = 'Room(4 beds)') 
                OR (RoomID = '$RoomID' AND OccStatus = 'Active' AND Occtype = 'Room(6 beds)')";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $EndDate = $row['OccDateEnd'];

        // Insert new occupancy record
        $stmtInsert = $conn->prepare("INSERT INTO occupancy (TenantID, RoomID, Occtype, OccDateStart, OccDateEnd, OccStatus) 
                                    VALUES (?, ?, ?, CURDATE(), ?, 'Active')");
        $stmtInsert->bind_param("isss", $TenantID, $RoomID, $Occtype, $EndDate);
        $stmtInsert->execute();

        // Update NumofTen and RoomType in rooms table
        $sqlUpdateRooms = "UPDATE rooms SET NumofTen = (SELECT COUNT(*) FROM occupancy WHERE RoomID = ? AND OccStatus = 'Active') WHERE RoomID = ?";
        $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
        $stmtUpdateRooms->bind_param("ss", $RoomID, $RoomID);
        $stmtUpdateRooms->execute();
        $stmtUpdateRooms->close();

        $isProcessed = true;
    }
}

// Update NumofTen in rooms table (increment for 'bedspacer' type)
if ($Occtype == 'Bedspacer') {
    static $isProcessed = false;
    
    if (!$isProcessed) {
    // Insert new occupancy record
    $stmtInsert = $conn->prepare("INSERT INTO occupancy (TenantID, RoomID, Occtype, OccDateStart, OccDateEnd, OccStatus) 
    VALUES (?, ?, ?, CURDATE(), ?, 'Active')");
    $stmtInsert->bind_param("isss", $TenantID, $RoomID, $Occtype, $EndDate);
    $stmtInsert->execute();

    // Update NumofTen and RoomType in rooms table
    $sqlUpdateRooms = "UPDATE rooms SET NumofTen = (SELECT COUNT(*) FROM occupancy WHERE RoomID = ? AND OccStatus = 'Active'), RoomType = 'Shared' WHERE RoomID = ?";
    $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
    $stmtUpdateRooms->bind_param("ss", $RoomID, $RoomID);
    $stmtUpdateRooms->execute();
    $stmtUpdateRooms->close();
    
    $isProcessed = true;
    }
}

// Update NumofTen in rooms table (increment for 'roomer' type)
if ($Occtype == 'Room(4 beds)' || $Occtype == 'Room(6 beds)') {
    static $isProcessed = false;

    if (!$isProcessed) {
    // Insert new occupancy record
    $stmtInsert = $conn->prepare("INSERT INTO occupancy (TenantID, RoomID, Occtype, OccDateStart, OccDateEnd, OccStatus) 
    VALUES (?, ?, ?, CURDATE(), ?, 'Active')");
    $stmtInsert->bind_param("isss", $TenantID, $RoomID, $Occtype, $EndDate);
    $stmtInsert->execute();
    
    // Update NumofTen and RoomType in rooms table
    $sqlUpdateRooms = "UPDATE rooms SET NumofTen = (SELECT COUNT(*) FROM occupancy WHERE RoomID = ? AND OccStatus = 'Active'), RoomType = 'Rented' WHERE RoomID = ?";
    $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
    $stmtUpdateRooms->bind_param("ss", $RoomID, $RoomID);
    $stmtUpdateRooms->execute();
    $stmtUpdateRooms->close();
    
    $isProcessed = true;
    }
}

$conn->close();

// After successfully adding a new rent, redirect to the same page
header('Location: ..\tenants.php');
exit();
?>
