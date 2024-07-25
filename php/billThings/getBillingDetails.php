<?php
include '../mysql.php';

$BillRefNo = $_GET['BillRefNo'];

$stmtBillInfo = $conn->prepare("SELECT *, DATE_FORMAT(b.BillingMonth, '%M') AS 'Month'
                                FROM billing b
                                JOIN occupancy o ON b.OccupancyID = o.OccupancyID
                                LEFT JOIN appliances a ON b.TenantAppID = a.TenantAppID
                                LEFT JOIN occtype oc ON o.Occtype = oc.Occtype
                                JOIN tenant t ON o.TenantID = t.TenantID
                                WHERE b.BillRefNo = ?");
$stmtBillInfo->bind_param('i', $BillRefNo);
$stmtBillInfo->execute();
$resultBillInfo = $stmtBillInfo->get_result();
$rowBillInfo = $resultBillInfo->fetch_assoc();

// Check if the result is not null
if ($rowBillInfo) {
    echo '
    <div>
        <div class="modal-header">
            <span class="close">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="modal-left">
                <img src="../../icons/logo_invert.png" alt="Munoz Boarding House Logo">
                <h2>Tenant Details</h2>
                <p id="tenant-details">' . htmlspecialchars($rowBillInfo['TenFname'] . ' ' . $rowBillInfo['TenMname'] . ' ' . $rowBillInfo['TenBarangay']) . '<br>' . htmlspecialchars($rowBillInfo['TenCity']) . '<br>' . htmlspecialchars($rowBillInfo['TenProv']) . ', Philippines</p>
                <p id="tenant-contact">' . htmlspecialchars($rowBillInfo['TenConNum']) . '<br>' . htmlspecialchars($rowBillInfo['TenEmail']) . '</p>
            </div>
            <div class="modal-right">
                <h2>Bill Reference Number: ' . htmlspecialchars($rowBillInfo['BillRefNo']) . '</h2>
                <table class="bill-table">
                    <tr>
                        <td>Tenant ID</td>
                        <td>' . htmlspecialchars($rowBillInfo['TenantID']) . '</td>
                        <td>Bill Date Issued</td>
                        <td>' . htmlspecialchars($rowBillInfo['BillDateIssued']) . '</td>
                    </tr>
                    <tr>
                        <td>Room</td>
                        <td>' . htmlspecialchars($rowBillInfo['RoomID']) . '</td>
                        <td>Bill Date Due</td>
                        <td>' . htmlspecialchars($rowBillInfo['BillDueDate']) . '</td>
                    </tr>
                    <tr>
                        <td>Bill Month</td>
                        <td>' . htmlspecialchars($rowBillInfo['Month']) . '</td>
                        <td>Payment Status</td>
                        <td>' . htmlspecialchars($rowBillInfo['BillStatus']) . '</td>
                    </tr>
                </table>
                <h3>Rent</h3>
                <table class="details-table">
                    <tr>
                        <td>Occupancy Rate</td>
                        <td>Php ' . htmlspecialchars($rowBillInfo['OccRate']) . '</td>
                    </tr>
                </table>
                <h3>Appliances</h3>
                <table class="details-table">
                    <tr>
                        <td>Number of Apps</td>
                        <td>' . htmlspecialchars($rowBillInfo['Quantity']) . '</td>
                    </tr>
                    <tr>
                        <td>Additional Apps</td>
                        <td>' . htmlspecialchars($rowBillInfo['AddQuantity']) . '</td>
                    </tr>
                    <tr>
                        <td>Rate per Appliance</td>
                        <td>Php ' . htmlspecialchars($rowBillInfo['appRate']) . '</td>
                    </tr>
                </table>
                <table class="total-table">
                    <tr>
                        <td>Bill Total</td>
                        <td>Php ' . htmlspecialchars($rowBillInfo['DueAmount']) . '</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>';
} else {
    echo 'No billing details found for the provided reference number.';
}
?>
