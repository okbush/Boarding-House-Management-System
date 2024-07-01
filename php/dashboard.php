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
    <title>Boarding House Management Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script defer src="script.js"></script>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="icons/logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul class="w-100">
            <li><a href="dashboard.php" class="active"><i class="fa fa-envelope-open-o"
                        aria-hidden="true"></i>Dashboard</a></li>
            <li><a href="#statistics"><i class="fa fa-bar-chart" aria-hidden="true"></i> Statistics</a></li>
            <li><a href="tenants.php"><i class="fa fa-address-book-o" aria-hidden="true"></i> Tenants</a></li>
            <li><a href="#rooms"><i class="fa fa-bed" aria-hidden="true"></i> Room Logs</a></li>
            <li><a href="#bills"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="maintenance.php"><i class="fa fa-wrench" aria-hidden="true"></i> Maintenance</a></li>
            <li><a href="#settings"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

        <div class="signout">
            <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
        </div>
    </div>

    <div class="Content">
        <div class="main-content">
            <div class="header">
                <h1>Welcome Back, User!</h1>
                <p>Here's what we have for you today!</p>
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

                <button id="addPayment">Add Payment</button>
                <div id="Payadd" class="modal">

                    <div class="modal-content">
                      <span class="close">&times;</span>
                      <form>
                        <label for="tenant-list">List of Tenants:</label>
                        <select id="tenant-list" name="tenant-list">
                            <option value="maria-detablurs">Maria P. Detablurs</option>
                        </select>
        
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" value="Maria">
        
                        <label for="middle-name">Middle Name:</label>
                        <input type="text" id="middle-name" name="middle-name" value="Pia">
        
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last-name" value="Detablurs">
        
                        <label for="payment-amount">Payment Amount:</label>
                        <input type="text" id="payment-amount" name="payment-amount" value="600.00">
        
                        <label for="payment-method">Payment Method:</label>
                        <select id="payment-method" name="payment-method">
                            <option value="cash">Cash</option>
                        </select>
        
                        <label for="payment-date">Payment Date:</label>
                        <input type="text" id="payment-date" name="payment-date" value="May 1, 2024 - May 31, 2024">
        
                        <button type="submit" class="add-btn">Add</button>
                    </form>
                    </div>
                  
                </div>



                <button id="addRent">Add New Rent</button>
                <div id="Rentadd" class="modal">

                    <div class="modal-content">
                      <span class="close">&times;</span>


                    <form>
                <label for="tenant">Tenant Assigned:</label>
                <input type="text" id="tenant" name="tenant" value="Maria P. Detablurs">

                <label for="room-code">Room Details:</label>
                <select id="room-code" name="room-code">
                    <option value="B10200">B10200</option>
                </select>
                <select id="occupancy-type" name="occupancy-type">
                    <option value="bed-spacer">Bed-spacer</option>
                </select>

                <label for="starting-date">Starting Date:</label>
                <input type="date" id="starting-date" name="starting-date" value="2024-04-01">

                <label for="payment-total">Payment Total:</label>
                <input type="text" id="payment-total" name="payment-total" value="600.00">

                <button type="submit" class="add-btn">Add</button>
            </form>
                    </div>
                    
                </div>
            </div>
        </div>


            <!-- Overview Section -->
            <div class="overview">
                <div class="card">
                    <div class="icon">
                        <img src="icons/total-residents-icon.png" alt="Total Residents Icon">
                    </div>
                    <div class="info">
                        <h2>30</h2>
                        <p>Total Residents</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icon">
                        <img src="icons/occupied-beds-icon.png" alt="Occupied Beds Icon">
                    </div>
                    <div class="info">
                        <h2>35</h2>
                        <p>Occupied Beds</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icon">
                        <img src="icons/available-beds-icon.png" alt="Available Beds Icon">
                    </div>
                    <div class="info">
                        <h2>04</h2>
                        <p>Available Beds</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icon">
                        <img src="icons/available-rooms-icon.png" alt="Available Rooms Icon">
                    </div>
                    <div class="info">
                        <h2>02</h2>
                        <p>Available Rooms</p>
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

        function addPayment() {
            var modal = document.getElementById("Payadd");
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

        function addRent() {
            var modal = document.getElementById("Rentadd");
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
