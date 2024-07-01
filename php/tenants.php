<?php
include 'mysql.php';

if (isset($_POST["TenantAdd"])) {
    $TenFname = $_POST["TenFname"];
    $TenLname = $_POST["TenLname"];
    $TenMname = $_POST["TenMname"];
    $TenHouseNum = $_POST["TenHouseNum"];
    $TenSt = $_POST["TenSt"];
    $TenBarangay = $_POST["TenBarangay"];
    $TenCity = $_POST["TenCity"];
    $TenProv = $_POST["TenProv"];
    $TenConNum = $_POST["TenConNum"];
    $TenEmail = $_POST["TenEmail"];
    $TenBdate = $_POST["TenBdate"];
    $TenGender = $_POST["TenGender"];
    $EmConFname = $_POST["EmConFname"];
    $EmConLname = $_POST["EmConLname"];
    $EmConMname = $_POST["EmConMname"];
    $EmConNum = $_POST["EmConNum"];
    $sql = "INSERT INTO TENANT (TenFname, TenLname, TenMname, TenHouseNum, TenSt, TenBarangay, TenCity, TenProv, TenConNum, TenEmail, TenBdate, TenGender, EmConFname, EmConLname, EmConMname, EmConNum) VALUES ('$TenFname', '$TenLname', '$TenMname', '$TenHouseNum', '$TenSt', '$TenBarangay', '$TenCity', '$TenProv', '$TenConNum', '$TenEmail', '$TenBdate', '$TenGender', '$EmConFname', '$EmConLname', '$EmConMname', '$EmConNum')";

    if (!in_array($TenGender, ['M', 'F'])) {
        echo '<script>alert("Invalid gender value. Please select either Male or Female.");</script>';
        exit;
    }

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("New Tenant created successfully");</script>';
    } else {
        echo '<script>alert("Error: ' . $sql . ' <br> ' . $conn->error . '");</script>';
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenants</title>
    <link rel="stylesheet" href="styles/tenants-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></scrip >
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script defer src="script.js"></script>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="icons/logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul class="w-100">
            <li><a href="dashboard.php"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="#statistics"><i class="fa fa-bar-chart" aria-hidden="true"></i> Statistics</a></li>
            <li><a href="tenants.php" class="active"><i class="fa fa-address-book-o" aria-hidden="true"></i>Tenants</a>
            </li>
            <li><a href="#rooms"><i class="fa fa-bed" aria-hidden="true"></i> Room Logs</a></li>
            <li><a href="#bills"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="maintenance.php"><i class="fa fa-wrench" aria-hidden="true"></i> Maintenance</a></li>
            <li><a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

        <div class="signout">
            <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
        </div>
    </div>

    <div class="Content">
        <div class="main-content">
            <div class="header">
                <h1>Tenants List</h1>
                <div class="buttons">
                    <button id="addTenant">Add Tenant</button>
                    <div id="Tenadd" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Add New Tenant</h2>
                            <form>
                                <div class="form-group">
                                    <label for="firstName">Name:</label>
                                    <input type="text" id="firstName" placeholder="First Name">
                                    <input type="text" id="middleName" placeholder="Middle Name">
                                    <input type="text" id="lastName" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <label for="birthDate">Birth Date:</label>
                                    <input type="date" id="birthDate">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender:</label>
                                    <select id="gender">
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="contactNumber">Contact Number:</label>
                                    <input type="text" id="contactNumber" placeholder="+63 9615053922">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" placeholder="mdetablurs@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="houseNumber" placeholder="House No.">
                                    <input type="text" id="street" placeholder="Street">
                                    <input type="text" id="barangay" placeholder="Barangay">
                                    <input type="text" id="city" placeholder="City">
                                    <input type="text" id="province" placeholder="Province">
                                </div>
                                <div class="form-group">
                                    <label for="emergencyContactName">Emergency Contact Name:</label>
                                    <input type="text" id="emergencyContactName" placeholder="First Name">
                                    <input type="text" id="emergencyContactMiddleInitial" placeholder="M">
                                    <input type="text" id="emergencyContactLastName" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <label for="emergencyContactNumber">Emergency Contact Number:</label>
                                    <input type="text" id="emergencyContactNumber" placeholder="+63 123456788">
                                </div>

                                <button type="submit">Add New Tenant</button>
                            </form>
                        </div>
                    </div>
                    <div class="TenNum">

                        <p>Current Number of Residents</p>

                        <div class="icon">
                            <img src="icons/total-residents-icon.png" alt="Total Residents Icon">
                        </div>

                        <div class="info">
                            <h2>30</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group; select">
                <label>Sort By:</label>
                <select>
                    <option>Alphabetically Z-A</option>
                    <option>Alphabetically A-Z</option>
                    <option>Most Recent</option>
                </select>
            </div>


            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tenants ID</th>
                            <th>Tenants Info</th>
                            <th>Status</th>
                            <th>Occupancy</th>
                            <th>Room Code</th>
                            <th>End Date</th>
                            <th>Action</th>
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
                                                                                           document.getElementById("addTenant").addEventListener("click", addTenant);
                                                                                           document.getElementById("addPayment").addEventListener("click", addPayment);
                                                                                           document.getElementById("addRent").addEventListener("click", addRent);

                                                                                           function addTenant() {
            var modal = document.getElementById("Tenadd");
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
        }
    </script>
</body>

</html>