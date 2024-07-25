<?php
include '../mysql.php';

$BillRefNo = $_GET['BillRefNo'];

// Prepare SQL code to fetch billing details based on Bill Reference Number
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
            <h2><b>Bill Reference Number: ' . htmlspecialchars($rowBillInfo['BillRefNo']) . '</h2>
            <div class="modal-left">
            <br>
                <h4><b>Tenant Details<b></h4>
                <p>Name: ' . htmlspecialchars($rowBillInfo['TenFname'] . ' ' . $rowBillInfo['TenMname'] . ' ' . $rowBillInfo['TenLname']) . '</p>
                <p>Address: ' . htmlspecialchars($rowBillInfo['TenBarangay']) . ' ' . htmlspecialchars($rowBillInfo['TenCity']) . ' ' . htmlspecialchars($rowBillInfo['TenProv']) . ', Philippines</p>
                <p>Contact Number: ' . htmlspecialchars($rowBillInfo['TenConNum']) . '</p>
                <p>Email: ' . htmlspecialchars($rowBillInfo['TenEmail']) . '</p>
            </div>

            <div class="modal-right">
                <table class="bill-table">
                <br>
                <h4><b>Bill Details<b></h4>
                        
                        <p>Bill Date Issued: ' . htmlspecialchars($rowBillInfo['BillDateIssued']) . '</p>
                        <p>Room: ' . htmlspecialchars($rowBillInfo['RoomID']) . '</p>
                        <p>Bill Date Due: ' . htmlspecialchars($rowBillInfo['BillDueDate']) . '</p>
                        <p>Date For: ' . htmlspecialchars($rowBillInfo['Month']) . '</p>
                        <p>Payment Status: ' . htmlspecialchars($rowBillInfo['BillStatus']) . '</p>
                </table>

                <h3><b>Rent</b></h3>
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
    </div>';
} else {
    // Output message if no billing details are found for the provided reference number
    echo 'No billing details found for the provided reference number.';
}
?>
