<?php
include 'mysql.php';

// SQL code to get the latest appliance rate from the database
$sql = "SELECT rate FROM appRate WHERE id = (SELECT MAX(id) FROM appRate) LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$appRate = $row['rate'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billings</title>

    <link href="https://fonts.googleapis.com/css2?family=Mallanna&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/billing-styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script defer src="script.js"></script>
    <script>
        // Refresh the page when the alert is dismissed
        document.querySelector(".alert-dismissible [data-dismiss='alert']").addEventListener("click", function () {
            window.location.href = window.location.href.split("?")[0] + "?";
        });
    </script>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="icons/logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul class="w-100">
            <li><a href="dashboard.php"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="tenants.php"><i class="fa fa-address-book-o" aria-hidden="true"></i> Tenants</a></li>
            <li><a href="roomLogs/index.php"><i class="fa fa-bed" aria-hidden="true"></i> Room</a></li>
            <li><a href="billing.php" class="active"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a>
            </li>
            <li><a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

    </div>

    <div class="Content">

        <div class="main-content">
            <header>
                <h1><b>Billings</b></h1>
                <p>See recent transactions and tenant’s billing status.</p>
            </header>

            <div class="statistics">
                <div class="stat-box">
                    <h2><b>
                            <?php
                            include 'mysql.php';

                            // SQL code to count the number of unpaid bills
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM billing WHERE BillStatus = 'Unpaid'");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo $row['COUNT(*)'];
                            ?>
                        </b>
                    </h2>
                    <p>Tenants with Unpaid Balances</p>
                </div>

                <div class="stat-box">
                    <h2><b>
                            <?php
                            include 'mysql.php';

                            // SQL code to count the number of overdue bills
                            $current_date = date('Y-m-d');
                            $overdue_bills_query = "SELECT COUNT(*) AS overdue_bills_count, SUM(DueAmount) AS overdue_bills_amount FROM billing WHERE BillStatus = 'Unpaid' AND BillDueDate < '$current_date'";
                            $overdue_bills_result = mysqli_query($conn, $overdue_bills_query);
                            $overdue_bills_data = mysqli_fetch_assoc($overdue_bills_result);
                            echo $overdue_bills_data['overdue_bills_count'];
                            ?>
                        </b>
                    </h2>
                    <p>Tenants with Overdue Bills</p>
                </div>
            </div>

            <div class="buttons-container">
                <div class="buttons">
                    <button id="addPayment">Rent Bill</button>
                    <button id="addAppPayment">Appliance Bill</button>
                </div>
                <div class="rate-update-container">
                    <form action="billThings/updateRate.php" method="post">
                        <input type="number" name="appRate" value="<?php echo $appRate ?>">
                        <input type="submit" value="Update Appliance Rate"
                            onclick="return confirm('Are you sure you want to update appliance rate?')">
                    </form>
                </div>
            </div>

            <div id="Payadd" class="modal">
                <div class="modal-content">
                    <span class="close">x</span>
                    <h2><b>Generate Rent Bill</b></h2>
                    <form method="post" action="billThings/rentBill.php">
                        <label for="tenant-list">List of Tenants:</label>
                        <select id="tenant-list" name="OccupancyID">
                            <?php
                            include 'mysql.php';

                            // SQL code to get a list of active tenants
                            $sql = "SELECT DISTINCT o.OccupancyID, CONCAT(t.TenFname,'', t.TenMname,'', t.TenLname) AS name
                                FROM occupancy o JOIN tenant t ON o.TenantID = t.TenantID
                                WHERE o.OccStatus = 'Active'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value=" . $row['OccupancyID'] . ">" . $row['name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No options available</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                        <input type="hidden" id="payment-date" name="payment-date" value="2024-07-19">
                        <button type="submit" class="add-btn"
                            onclick="return confirm('Are you sure you want to generate bill')">Add</button>
                    </form>
                </div>
            </div>

            <!-- Bill Modal -->
            <div id="billModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">×</span>
                    <p id="BillDetails"></p>
                </div>
            </div>

            <div id="appPayadd" class="modal">
                <div class="modal-content">
                    <span class="close">×</span>
                    <h2><b>Generate Appliance Bill</b></h2>
                    <form method="post" action="billThings/appBill.php">
                        <label for="tenant-list">List of Tenants:</label>
                        <select id="tenant-list" name="OccupancyID">

                            <?php
                            include 'mysql.php';

                            // SQL code to get a list of active tenants
                            $sql = "SELECT DISTINCT o.OccupancyID, CONCAT(t.TenFname,'', t.TenMname,'', t.TenLname) AS name
                                FROM occupancy o JOIN tenant t ON o.TenantID = t.TenantID
                                WHERE o.OccStatus = 'Active'";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value=" . $row['OccupancyID'] . ">" . $row['name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No options available</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                        <input type="hidden" id="payment-date" name="payment-date" value="2024-07-19">
                        <button type="submit" class="add-btn"
                            onclick="return confirm('Are you sure you want to generate bill')">Add</button>
                    </form>
                </div>
            </div>

        </div>

        <div class="billing-table">
            <div class="search-bar-container">
                <input type="text" id="searchInput" class="search-bar" placeholder="Search">
                <button class="search-button">Search</button>
            </div>

            <div class="filter-container">
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="Unpaid">Unpaid</option>
                    <option value="Paid">Paid</option>
                </select>

                <select id="type-filter">
                    <option value="">All Types</option>
                    <option value="Rent">Rent</option>
                    <option value="Appliances">Appliances</option>
                </select>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Tenant</th>
                            <th>Date Issued</th>
                            <th>Date For</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'mysql.php';

                        // SQL code to get all billing details
                        $query = "SELECT * , DATE_FORMAT(b.BillingMonth, '%M') as 'Month'
                                  FROM billing b JOIN occupancy o ON b.OccupancyID = o.OccupancyID JOIN tenant t ON o.TenantID = t.TenantID
                                  ORDER BY b.BillRefNo DESC";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                <td>' . $row['BillType'] . '</td>
                                <td>' . $row['BillStatus'] . '</td>
                                <td>' . $row['TenFname'] . " " . $row['TenMname'] . " " . $row['TenLname'] . '</td>
                                <td>' . $row['BillDateIssued'] . '</td>
                                <td>' . $row['Month'] . '</td>
                                <td>' . $row['BillDueDate'] . '</td>
                                <td>' . $row['DueAmount'] . '</td>
                                <td>
                                <span style="display: flex;">

                                <button onclick="openModal(' . $row['BillRefNo'] . ')">View</button>

                                <form action="billThings/updateBillStatus.php" method="post">
                                <input type="hidden" name="BillRefNo" value="' . $row['BillRefNo'] . '">
                                <button type="submit" onclick="return confirm(\'Are you sure you want to mark this bill as paid?\')">Paid</button>
                                </form>

                                <form action="billThings/deleteBill.php" method="post">
                                <input type="hidden" name="BillRefNo" value="' . $row['BillRefNo'] . '">
                                <button type="submit" onclick="return confirm(\'Are you sure you want to delete this bill?\')">Delete</button>
                                </form>

                                <span>
                                </td>
                                </tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8">No bills found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.getElementById("addPayment").addEventListener("click", addPayment);
        document.getElementById("addAppPayment").addEventListener("click", addAppPayment);

        function addPayment() {
            var modal = document.getElementById("Payadd");
            var span = modal.getElementsByClassName("close")[0];
            var submitButton = modal.querySelector("button[type='submit']");

            modal.style.display = "block";
            span.onclick = function () {
                modal.style.display = "none";
            }
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        function addAppPayment() {
            var modal = document.getElementById("appPayadd");
            var span = modal.getElementsByClassName("close")[0];
            var submitButton = modal.querySelector("button[type='submit']");

            modal.style.display = "block";
            span.onclick = function () {
                modal.style.display = "none";
            }
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            var closeButtons = document.querySelectorAll('.close');

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    var alert = this.closest('.alert');
                    var url = window.location.href;
                    var index = url.indexOf('?');

                    if (index !== -1) {
                        url = url.substring(0, index);
                        window.history.replaceState({}, document.title, url);
                    }

                    alert.style.display = 'none';
                });
            });
        });

        function openModal(BillRefNo) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var BillDetails = document.getElementById("BillDetails");
                        BillDetails.innerHTML = xhr.responseText;

                        var modal = document.getElementById("billModal");
                        var span = modal.getElementsByClassName("close")[0];

                        modal.style.display = "block";
                        span.onclick = function () {
                            modal.style.display = "none";
                        }
                        window.onclick = function (event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }
                    } else {
                        console.error('Error fetching room details:', xhr.status);
                    }
                }
            };

            xhr.open("GET", "billThings/getBillingDetails.php?BillRefNo=" + BillRefNo, true);
            xhr.send();
        }

        document.querySelector(".search-button").addEventListener("click", function () {
            var input = document.querySelector(".search-bar").value.toLowerCase();
            var status = document.querySelector("#status-filter").value;
            var type = document.querySelector("#type-filter").value;
            var table = document.querySelector("table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellValue = cells[j].textContent || cells[j].innerText;

                    if (cellValue.toLowerCase().indexOf(input) > -1) {
                        found = true;
                        break;
                    }
                }

                if (status !== "" && cells[1].textContent !== status) {
                    found = false;
                }

                if (type !== "" && cells[0].textContent !== type) {
                    found = false;
                }

                if (found) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        });

        document.querySelector("#status-filter").addEventListener("change", function () {
            filterTable();
        });

        document.querySelector("#type-filter").addEventListener("change", function () {
            filterTable();
        });

        function filterTable() {
            var status = document.querySelector("#status-filter").value;
            var type = document.querySelector("#type-filter").value;
            var input = document.querySelector(".search-bar").value.toLowerCase();
            var table = document.querySelector("table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var statusCell = cells[1];
                var statusValue = statusCell.textContent || statusCell.innerText;
                var typeCell = cells[0];
                var typeValue = typeCell.textContent || typeCell.innerText;
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellValue = cells[j].textContent || cells[j].innerText;

                    if (cellValue.toLowerCase().indexOf(input) > -1) {
                        found = true;
                        break;
                    }
                }

                if (status !== "" && cells[1].textContent !== status) {
                    found = false;
                }

                if (type !== "" && cells[0].textContent !== type) {
                    found = false;
                }

                if (found) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>

</body>

</html>