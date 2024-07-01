<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <link rel="stylesheet" href="styles/maintenance-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <img src="icons/logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul class="w-100">
            <li><a href="dashboard.php"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="#statistics"><i class="fa fa-bar-chart" aria-hidden="true"></i> Statistics</a></li>
            <li><a href="tenants.php"><i class="fa fa-address-book-o" aria-hidden="true"></i> Tenants</a></li>
            <li><a href="#rooms"><i class="fa fa-bed" aria-hidden="true"></i> Room Logs</a></li>
            <li><a href="#bills"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="maintenance.php" class="active"><i class="fa fa-wrench" aria-hidden="true"></i> Maintenance</a>
            </li>
            <li><a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

        <div class="signout">
            <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
        </div>
    </div>

    <div class="Content">
        <div class="main-content">
            <div class="header">
                <h1>Maintenance</h1>

                <div class="buttons">
                    <button id="addMaintenanceButton">Add Maintenance</button>
                    <div id="addMaintenanceModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Add Maintenance Report</h2>
                            <form method="post" action="maintenance.php">
                                <div class="form-group">
                                    <label for="code">Room Code:</label>
                                    <input type="text" id="code" name="RoomID" placeholder="Enter room code..."
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="MaintDate">Date:</label>
                                    <input type="date" name="MaintDate" id="MaintDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="MaintDesc">Description:</label>
                                    <input type="text" id="MaintDesc" name="MaintDesc"
                                        placeholder="Enter description..." required>
                                </div>
                                <div class="form-group">
                                    <label for="MaintStatus">Status:</label>
                                    <select id="MaintStatus" name="MaintStatus" required>
                                        <option value="" disabled>Select Status...</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="PendingF">Pending</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="MaintCost">Maintenance Cost:</label>
                                    <input type="text" id="MaintCost" name="MaintCost" placeholder="Enter cost..."
                                        required>
                                </div>
                                <input type="submit" name="addMaintenance" value="Add Maintenance">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Maintenance ID</th>
                                <th>Room Code</th>
                                <th>Staff</th>
                                <th>Description</th>
                                <th>Cost</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1234</td>
                                <td>B10101</td>
                                <td>Staff1</td>
                                <td>Door repair</td>
                                <td>250.00</td>
                                <td>May 20, 2024</td>
                                <td>On-going</td>
                            </tr>
                            <tr>
                                <td>4321</td>
                                <td>B20102</td>
                                <td>Staff2</td>
                                <td>General cleaning</td>
                                <td>500.00</td>
                                <td>May 30, 2024</td>
                                <td>On-going</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <span class="page active">1</span>
                        <span class="page">2</span>
                        <span class="page">Next</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal display functionality
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById("addMaintenanceModal");
            var btn = document.getElementById("addMaintenanceButton");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function () {
                modal.style.display = "block";
            }

            span.onclick = function () {
                modal.style.display = "none";
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>

</body>

</html>
