<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "im2database";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    header('../login/login.php');
    exit;
}
function addStaffModal($conn) {
    $sql = "SELECT StaffID, CONCAT(StaffFname, ' ', StaffMI, ' ', StaffLname) AS FullName, StaffRole 
    FROM staff";
    $staffs = $conn->query($sql);
    echo '<button type="button" id="addstaffform" class="edit-btn">Add New Staff</button>';
    if ($staffs->num_rows > 0) {
        echo '<h2>Staff List</h2>';
        echo '<table>';
        echo '<tr><th>Staff ID</th><th>Full Name</th><th>Role</th></tr>';
        
        while ($staff = $staffs->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $staff['StaffID'] . '</td>';
            echo '<td>' . $staff['FullName'] . '</td>';
            echo '<td>' . $staff['StaffRole'] . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';

    } else {
        echo "No staff found.";
    }
}

// AJAX for add staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addStaffModal') {
    addStaffModal($conn);
    exit;
}
// AJAX for getRatesContent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getRatesContent') {
    getRatesContent($conn);
    exit;
}
// room rates
function getRatesContent($conn) {
    // Define the room types
    $roomTypes = ['bedspacer', 'Room(4 beds)', 'Room(6 beds)'];

    $ratesFound = false; // Flag to check if any rates are found

    foreach ($roomTypes as $type) {
        // Escape the room type to prevent SQL injection
        $escapedRoomType = $conn->real_escape_string($type);
        
        // Query to retrieve rates based on room type
        $sql = "SELECT OccType, OccRate FROM occtype WHERE OccType = '$escapedRoomType'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $rate = $result->fetch_assoc();
                // Ensure the array keys match the column names in the database
                $rateType = htmlspecialchars($rate['OccType'] ?? 'Unknown');
                $rateAmount = htmlspecialchars($rate['OccRate'] ?? '0.00');
                
                // Display content based on room type
                echo "<p><strong>Room Type:</strong> " . $rateType . "<br> 
                      <div class='rate-container'>
                          <span class='rounded-rectangle'>$" . $rateAmount . "</span>
                          <button class='edit-button' data-room-type='" . $rateType . "'>Edit</button>
                      </div>
                      <div style='text-align:center;'>Monthly Cost</div>
                      </p><br>"; // Added line break after each room type's output
                $ratesFound = true; // Set flag to true if rates are found
            }
        } else {
            // Handle SQL query error
            echo "Error executing query: " . $conn->error;
        }
    }

    // If no rates were found for any room type, display a message
    if (!$ratesFound) {
        echo "No rates found for the selected room type.";
    }
}
// -------------------------------------------- edit form for rates -------------------------------------
// Check if the request is to update the rate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateRate') {
    // Sanitize and prepare data for the query
    $roomType = $conn->real_escape_string($_POST['roomType']);
    $newRate = $conn->real_escape_string($_POST['newRate']);

    // SQL query to update the rate
    $sql = "UPDATE occtype SET OccRate = '$newRate' WHERE OccType = '$roomType'";
    
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Respond with success
        echo json_encode(['success' => true]);
    } else {
        // Respond with failure and error message
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}
// ------------------------------------------ add staff form --------------------------------------------
// Check if the request is to add staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addstaffform') {
    // Sanitize and prepare data for the query
    $staffFname = $conn->real_escape_string($_POST['staffFname']);
    $staffLname = $conn->real_escape_string($_POST['staffLname']);
    $staffMI = $conn->real_escape_string($_POST['staffMI']);
    $staffRole = $conn->real_escape_string($_POST['staffRole']);
    $staffEmail = $conn->real_escape_string($_POST['staffEmail']);
    $staffConNum = $conn->real_escape_string($_POST['staffConNum']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $securityQuestion = $conn->real_escape_string($_POST['security_question']);
    $securityAnswer = $conn->real_escape_string($_POST['security_answer']);

    // Check if the username already exists in the database
    $check_username_sql = "SELECT * FROM staff WHERE username = '$staffEmail'";
    $check_username_result = $conn->query($check_username_sql);
    if ($check_username_result->num_rows > 0) {
        // Username already exists
        echo json_encode(['success' => false, 'error' => 'Email already exists. Please choose another email.']);
        exit;
    }

    // Check if the password meets the minimum length requirement (e.g., 8 characters)
    if (strlen($password) < 8) {
        // Password too short
        echo json_encode(['success' => false, 'error' => 'Password should be at least 8 characters long. Please choose another password.']);
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // SQL query to insert staff data
    $insert_sql = "INSERT INTO staff (StaffFname, StaffLname, StaffMI, StaffRole, StaffEmail, StaffConNum, username, password, security_question, security_answer)
            VALUES ('$staffFname', '$staffLname', '$staffMI', '$staffRole', '$staffEmail', '$staffConNum', '$username', '$hashed_password', '$securityQuestion', '$securityAnswer')";

    // Execute the query
    $insert_result = $conn->query($insert_sql);
    if ($insert_result === TRUE) {
        // Respond with success
        echo json_encode(['success' => true]);
    } else {
        // Respond with failure and error message
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}

function updateUserDetails($conn, $userId, $newUsername, $newPassword) {
    // Sanitize and prepare data for the query
    $newUsernamesql = $conn->real_escape_string($newUsername);
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the new password

    // Prepare the SQL query with placeholders
    $stmt = $conn->prepare("UPDATE staff SET username = ?, password = ? WHERE StaffID = ?");
    
    if ($stmt === false) {
        // Log the error message (for debugging purposes)
        error_log('Prepare failed: ' . $conn->error);
        return ['success' => false, 'error' => 'Prepare failed: ' . $conn->error];
    }

    // Bind parameters
    $stmt->bind_param("ssi", $newUsernamesql, $hashedPassword, $userId);

    // Execute the query
    if ($stmt->execute()) {
        $stmt->close();
        // Log success message (for debugging purposes)

        return ['success' => true];
    } else {
        $stmt->close();
        // Log the error message (for debugging purposes)

        return ['success' => false, 'error' => 'Execute failed: ' . $stmt->error];
    }
}



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateDetails') {
    session_start(); // Start the session

    // Ensure the user is logged in
    if (!isset($_SESSION['staff_id'])) {
        echo json_encode(['success' => false, 'error' => 'User not logged in']);
        exit;
    }

    // Validate input data
    if (empty($_POST['newUsername']) || empty($_POST['newPassword'])) {
        echo json_encode(['success' => false, 'error' => 'Username and Password are required']);
        exit;
    }




    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit;
    }

    // Call the function to update user details
    $response = updateUserDetails($conn, $_SESSION['staff_id'], $_POST['newUsername'], $_POST['newPassword']);
    
    // Output the response
    echo json_encode($response);
    
    // Close the connection
    $conn->close();
    exit;
}

?>
