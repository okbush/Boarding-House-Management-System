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
    <link rel="stylesheet" href="dashboard.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script defer src="script.js"></script>
</head>
<body>
  
    <div class="sidebar">
        <div class="logo">
            <img src="logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul class="w-100">
            <li><a href="#dashboard" class="active"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="#statistics"><i class="fa fa-bar-chart" aria-hidden="true"></i> Statistics</a></li>
            <li><a href="#residents"><i class="fa fa-address-book-o" aria-hidden="true"></i> Residents</a></li>
            <li><a href="#rooms"><i class="fa fa-bed" aria-hidden="true"></i> Room Logs</a></li>
            <li><a href="#bills"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="#maintain"><i class="fa fa-wrench" aria-hidden="true"></i> Maintenance</a></li>
            <li><a href="#settings"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

        <div class="signout">
            <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
        </div>
    </div>
    
<div class="Content">

    <div class="main-content">
        <div class="header">
            <h1>Welcome Back, Juan!</h1>
            <p>Here's what we have for you today!</p>
            <div class="buttons">

                <button id="addTenant">Add Tenant</button>
                <div id="Tenadd" class="modal">

                    <div class="modal-content">
                      <span class="close">&times;</span>
                      <h2>Add New Tenant</h2>
                      <form method="post" action="index1.php">
                        <div class="form-group">
                            <label for="firstName">Name:</label>
                            <input type="text" id="firstName" name="TenFname" placeholder="First Name" required>
                            <input type="text" id="middleName" name="TenLname" placeholder="Middle Name" required>
                            <input type="text" id="lastName" name="TenMname" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <label for="birthDate">Birth Date:</label>
                            <input type="date" name="TenBdate" id="birthDate" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="TenGender" required>
                                <option value="" disabled>Select Gender</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Contact Number:</label>
                            <input type="text" id="contactNumber" name="TenConNum" placeholder="+63 9615053922" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="TenEmail" placeholder="mdetablurs@gmail.com" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" id="houseNumber" name="TenHouseNum" placeholder="House No." required>
                            <input type="text" id="street" name="TenSt" placeholder="Street" required>
                            <input type="text" id="barangay" name="TenBarangay" placeholder="Barangay" required>
                            <input type="text" id="city" name="TenCity" placeholder="City" required>
                            <input type="text" id="province" name="TenProv" placeholder="Province" required>
                        </div>
                        <div class="form-group">
                            <label for="emergencyContactName">Emergency Contact Name:</label>
                            <input type="text" id="emergencyContactName" name="EmConFname" placeholder="First Name" required>
                            <input type="text" id="emergencyContactMiddleInitial" name="EmConLname" placeholder="M" required>
                            <input type="text" id="emergencyContactLastName" name="EmConMname" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <label for="emergencyContactNumber">Emergency Contact Number:</label>
                            <input type="text" id="emergencyContactNumber" name="EmConNum" placeholder="+63 123456788" required>
                        </div>
                        <div class="form-group">
                            <label for="numberOfAppliances">Number of Appliances:</label>
                            <input type="number" id="numberOfAppliances" value="3">
                        </div>
                        <input type="submit" name="TenantAdd" value="Add New Tenant"></button>
                      </form>
                    </div>
                  
                </div>



                <button id="addPayment">Add Payment</button>
                <div id="Payadd" class="modal">

                    <div class="modal-content">
                      <span class="close">&times;</span>
                      <p>Some text in the Modal.. for payment</p>
                    </div>
                  
                </div>



                <button id="addRent">Add New Rent</button>
                <div id="Rentadd" class="modal">

                    <div class="modal-content">
                      <span class="close">&times;</span>
                      <p>Some text in the Modal.. for rent</p>
                    </div>
                    
                </div>
            </div>
        </div>



        <div class="overview">
            <div class="card">
                <div class="icon">
                    <img src="total-residents-icon.png" alt="Total Residents Icon">
                </div>
                <div class="info">
                    <h2>30</h2>
                    <p>Total Residents</p>
                </div>
            </div>
            <div class="card">
                <div class="icon">
                    <img src="occupied-beds-icon.png" alt="Occupied Beds Icon">
                </div>
                <div class="info">
                    <h2>35</h2>
                    <p>Occupied Beds</p>
                </div>
            </div>
            <div class="card">
                <div class="icon">
                    <img src="available-beds-icon.png" alt="Available Beds Icon">
                </div>
                <div class="info">
                    <h2>04</h2>
                    <p>Available Beds</p>
                </div>
            </div>
            <div class="card">
                <div class="icon">
                    <img src="available-rooms-icon.png" alt="Available Rooms Icon">
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
        var btn = document.getElementById("addTenant");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        }

        function addPayment() {
        var modal = document.getElementById("Payadd");
        var btn = document.getElementById("addPayment");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        }

        function addRent() {
        var modal = document.getElementById("Rentadd");
        var btn = document.getElementById("addRent");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        }
    </script>
</body>
</html>
