<?php
include '../mysql.php';

$TenantID = $_POST["TenantID"];
$RoomID = $_POST["RoomID"];
$Occtype = $_POST["occupancy-type"];
$EndDate = date('Y-m-d', strtotime("+" . $_POST['ending-date'] . " months"));
$quantity = $_POST['Quantity'];

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

        // Update RoomStatus in rooms table
        $stmtUpdateRoomStatus = $conn->prepare("UPDATE rooms SET RoomStatus = CASE 
        WHEN NumofTen = 0 THEN 'Empty'
        WHEN NumofTen > 0 AND NumofTen < CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Partial'
        WHEN NumofTen = CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Full'
        END
        WHERE RoomID = ?");
        $stmtUpdateRoomStatus->bind_param("s", $RoomID);
        $stmtUpdateRoomStatus->execute();
        $stmtUpdateRoomStatus->close();

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

    $stmt = $conn->prepare("SELECT OccRate FROM occtype WHERE Occtype =?");
    $stmt->bind_param("s", $Occtype);
    $stmt->execute();
    $stmt->bind_result($OccRate);
    $stmt->fetch();
    $stmt->close();

    $OccupancyID = $stmtInsert->insert_id;
    
    $stmtApp = $conn->prepare("INSERT INTO appliances (OccupancyID, Quantity) VALUES (?, ?)");
    $stmtApp->bind_param("ii", $OccupancyID, $quantity);
    $stmtApp->execute();

    $TenantAppID = $stmtApp->insert_id;

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

    $BillDateIssued = date('Y-m-d');
    $BillDueDate = date('Y-m-d', strtotime($BillDateIssued.'+ 1 month'));
    $Rtype = 'Rent';
    $Atype = 'Appliances';
    $Status = 'Paid';

    $appDue = $appRate * $quantity;
    $addQuantity = 0;

        $stmtRent = $conn->prepare("INSERT INTO billing (OccupancyID, OccRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtRent->bind_param("iisssiss", $OccupancyID, $OccRate, $BillDateIssued, $BillDueDate, $BillDueDate, $OccRate, $Status, $Rtype);
    $stmtRent->execute();

    $stmtApp = $conn->prepare("INSERT INTO billing (OccupancyID, TenantAppID, Quantity, AddQuantity, appRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtApp->bind_param("iiiiisssdss", $OccupancyID, $TenantAppID, $quantity, $addQuantity, $appRate, $BillDateIssued, $BillDueDate, $BillDueDate, $appDue , $Status, $Atype);
    $stmtApp->execute();


    // Update NumofTen and RoomType in rooms table
    $sqlUpdateRooms = "UPDATE rooms SET NumofTen = (SELECT COUNT(*) FROM occupancy WHERE RoomID = ? AND OccStatus = 'Active'), RoomType = 'Shared' WHERE RoomID = ?";
    $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
    $stmtUpdateRooms->bind_param("ss", $RoomID, $RoomID);
    $stmtUpdateRooms->execute();
    $stmtUpdateRooms->close();
    
    // Update RoomStatus in rooms table
    $stmtUpdateRoomStatus = $conn->prepare("UPDATE rooms SET RoomStatus = CASE 
                                                WHEN NumofTen = 0 THEN 'Empty'
                                                WHEN NumofTen > 0 AND NumofTen < CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Partial'
                                                WHEN NumofTen = CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Full'
                                                END
                                            WHERE RoomID = ?");
    $stmtUpdateRoomStatus->bind_param("s", $RoomID);
    $stmtUpdateRoomStatus->execute();
    $stmtUpdateRoomStatus->close();
    
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

    $stmt = $conn->prepare("SELECT OccRate FROM occtype WHERE Occtype =?");
    $stmt->bind_param("s", $Occtype);
    $stmt->execute();
    $stmt->bind_result($OccRate);
    $stmt->fetch();
    $stmt->close();

    $OccupancyID = $stmtInsert->insert_id;
    
    $stmtApp = $conn->prepare("INSERT INTO appliances (OccupancyID, Quantity) VALUES (?, ?)");
    $stmtApp->bind_param("ii", $OccupancyID, $quantity);
    $stmtApp->execute();

    $TenantAppID = $stmtApp->insert_id;

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

    $BillDateIssued = date('Y-m-d');
    $BillDueDate = date('Y-m-d', strtotime($BillDateIssued.'+ 1 month'));
    $Rtype = 'Rent';
    $Atype = 'Appliances';
    $Status = 'Paid';

    $appDue = $appRate * $quantity;
    $addQuantity = 0;

    $stmtRent = $conn->prepare("INSERT INTO billing (OccupancyID, OccRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtRent->bind_param("iisssiss", $OccupancyID, $OccRate, $BillDateIssued, $BillDueDate, $BillDueDate, $OccRate, $Status, $Rtype);
    $stmtRent->execute();

    $stmtApp = $conn->prepare("INSERT INTO billing (OccupancyID, TenantAppID, Quantity, AddQuantity, appRate, BillDateIssued, BillingMonth, BillDueDate, DueAmount, BillStatus, BillType)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtApp->bind_param("iiiiisssdss", $OccupancyID, $TenantAppID, $quantity, $addQuantity, $appRate, $BillDateIssued, $BillDueDate, $BillDueDate, $appDue , $Status, $Atype);
    $stmtApp->execute();
    
    // Update NumofTen and RoomType in rooms table
    $sqlUpdateRooms = "UPDATE rooms SET NumofTen = (SELECT COUNT(*) FROM occupancy WHERE RoomID = ? AND OccStatus = 'Active'), RoomType = 'Rented' WHERE RoomID = ?";
    $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
    $stmtUpdateRooms->bind_param("ss", $RoomID, $RoomID);
    $stmtUpdateRooms->execute();
    $stmtUpdateRooms->close();

    // Update RoomStatus in rooms table
    $stmtUpdateRoomStatus = $conn->prepare("UPDATE rooms SET RoomStatus = CASE 
    WHEN NumofTen = 0 THEN 'Empty'
    WHEN NumofTen > 0 AND NumofTen < CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Partial'
    WHEN NumofTen = CASE WHEN Capacity = '4' THEN 4 WHEN Capacity = '6' THEN 6 END THEN 'Full'
    END
    WHERE RoomID = ?");
    $stmtUpdateRoomStatus->bind_param("s", $RoomID);
    $stmtUpdateRoomStatus->execute();
    $stmtUpdateRoomStatus->close();
    
    $isProcessed = true;
    }
}

$conn->close();

// After successfully adding a new rent, redirect to the same page
header('Location: ..\tenants.php');
exit();
?>