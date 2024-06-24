<?php
include 'mysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("New Tenant created successfully");</script>';
    } else {
        echo '<script>alert("Error: ' . $sql . ' <br> ' . $conn->error . '");</script>';
    }

    $conn->close();
    header("Location: index_employee.php");
    exit();
}

if (isset($_GET['id'])) {
    $TenantID = $_GET['id'];
    $sql = "SELECT TenantID, TenFname, TenLname, TenMname, TenHouseNum, TenSt, TenBarangay, TenCity, TenProv, TenConNum, TenEmail, TenBdate, TenGender, EmConFname, EmConLname, EmConMname, EmConNum FROM im2database WHERE TenantID=$TenantID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tenant</title>
    <link rel="stylesheet" href="dashboard.css">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo">
            <img src="logo.png" alt="Munoz Boarding House Logo">
        </div>

        <ul>
            <li><a href="#" class="active"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Statistics</a></li>
            <li><a href="#"><i class="fa fa-address-book-o" aria-hidden="true"></i> Residents</a></li>
            <li><a href="#"><i class="fa fa-bed" aria-hidden="true"></i> Room Logs</a></li>
            <li><a href="#"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="#"><i class="fa fa-wrench" aria-hidden="true"></i> Maintenance</a></li>
            <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

        <div class="signout">
            <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
        </div>
    </div>

<div class="Content">
    <div class="main-content">
    <div class="overview">
    <form method="post" action="index.php">
        <div class="form-group">
            <label for="firstName">Name:</label>
            <input type="text" id="firstName" name="TenFname" placeholder="First Name">
            <input type="text" id="middleName" name="TenLname" placeholder="Middle Name">
            <input type="text" id="lastName" name="TenMname" placeholder="Last Name">
        </div>
        <div class="form-group">
            <label for="birthDate">Birth Date:</label>
            <input type="date" name="TenBdate" id="birthDate">
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="TenGender">
                <option value="M" selected>M</option>
                <option value="F">F</option>
            </select>
        </div>
        <div class="form-group">
            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="TenConNum" placeholder="+63 9615053922">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="TenEmail" placeholder="mdetablurs@gmail.com">
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="houseNumber" name="TenHouseNum" placeholder="House No.">
            <input type="text" id="street" name="TenSt" placeholder="Street">
            <input type="text" id="barangay" name="TenBarangay" placeholder="Barangay">
            <input type="text" id="city" name="TenCity" placeholder="City">
            <input type="text" id="province" name="TenProv" placeholder="Province">
        </div>
        <div class="form-group">
            <label for="emergencyContactName">Emergency Contact Name:</label>
            <input type="text" id="emergencyContactName" name="EmConFname" placeholder="First Name">
            <input type="text" id="emergencyContactMiddleInitial" name="EmConLname" placeholder="M">
            <input type="text" id="emergencyContactLastName" name="EmConMname" placeholder="Last Name">
        </div>
        <div class="form-group">
            <label for="emergencyContactNumber">Emergency Contact Number:</label>
            <input type="text" id="emergencyContactNumber" name="EmConNum" placeholder="+63 123456788">
        </div>
        <div class="form-group">
            <label for="numberOfAppliances">Number of Appliances:</label>
            <input type="number" id="numberOfAppliances" value="3">
        </div>
        <input type="submit" value="Add New Tenant"></button>
    </form>
    <a href="index1.php">Back to Tenant List</a> 
    </div>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
