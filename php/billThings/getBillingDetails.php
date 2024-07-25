<?php 
include '../mysql.php';

$BillRefNo = $_GET['BillRefNo'];

$stmtBillInfo = $conn->prepare("SELECT * , DATE_FORMAT(b.BillingMonth, '%M') AS 'Month'
                                FROM billing b JOIN occupancy o ON b.OccupancyID = o.OccupancyID JOIN appliances a ON b.TenantAppID = a.TenantAppID JOIN occtype oc ON o.Occtype = oc.Occtype
                                JOIN tenant t ON o.TenantID = t.TenantID WHERE b.BillRefNo =?");
$stmtBillInfo->bind_param('i', $BillRefNo);
$stmtBillInfo->execute();
$resultBillInfo = $stmtBillInfo->get_result();
$rowBillInfo = $resultBillInfo->fetch_assoc();


echo '
<div>
    <div class="modal-header">
        <span class="close">&times;</span>
    </div>
    
    <div class="modal-body">
        <div class="modal-left">
            <img src="../../icons/logo_invert.png" alt="Munoz Boarding House Logo">
            <h2>Tenant Details</h2>
            <p id="tenant-details">'. $rowBillInfo['TenFname'] . " " . $rowBillInfo['TenMname'] . " " . $rowBillInfo['TenBarangay'] .'<br>'. $rowBillInfo['TenCity'] .'<br>'. $rowBillInfo['TenProv'] .', Philippines</p>
            <p id="tenant-contact">'. $rowBillInfo['TenConNum'] .'<br>'. $rowBillInfo['TenEmail'] .'</p>
        </div>
        <div class="modal-right">
            <h2>Bill Reference Number: '. $rowBillInfo['BillRefNo'] .'</h2>
            <table class="bill-table">
                <tr>
                    <td>Tenant ID</td>
                    <td>'. $rowBillInfo['TenantID'] .'</td>
                    <td>Bill Date Issued</td>
                    <td>'. $rowBillInfo['BillDateIssued'] .'</td>
                </tr>
                <tr>
                    <td>Room</td>
                    <td>'. $rowBillInfo['RoomID'] .'</td>
                    <td>Bill Date Due</td>
                    <td>'. $rowBillInfo['BillDueDate'] .'</td>
                </tr>
                <tr>
                    <td>Bill Month</td>
                    <td>'. $rowBillInfo['Month'] .'</td>
                    <td>Payment Status</td>
                    <td>'. $rowBillInfo['BillStatus'] .'</td>
                </tr>
            </table>
            <h3>Rent</h3>
            <table class="details-table">
                <tr>
                    <td>Occupancy Rate</td>
                    <td>Php '. $rowBillInfo['OccRate'] .'</td>
                </tr>
            </table>
            <h3>Appliances</h3>
            <table class="details-table">
                <tr>
                    <td>Number of Apps</td>
                    <td>'. $rowBillInfo['Quantity'] .'</td>
                </tr>
                <tr>
                    <td>Additional Apps</td>
                    <td>'. $rowBillInfo['AddQuantity'] .'</td>
                </tr>
                <tr>
                    <td>Rate per Appliance</td>
                    <td>Php '. $rowBillInfo['appRate'] .'</td>
                </tr>
            </table>
            <table class="total-table">
                <tr>
                    <td>Bill Total</td>
                    <td>Php '. $rowBillInfo['DueAmount'] .'</td>
                </tr>
            </table>
        </div>
    </div>
</div>';
?>