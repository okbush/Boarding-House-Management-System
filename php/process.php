<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "im2database";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();    

// Check if the user is logged in

function hasPrivilege($requiredPrivilege) {
    // Ensure session is started
    if (!isset($_SESSION['staff_id'])) {
        return false;
    }

    // Database connection
    global $conn; // Ensure $conn is accessible

    // Get the current staff member's role
    $staffID = $_SESSION['staff_id'];
    $stmt = $conn->prepare("SELECT StaffRole FROM staff WHERE username = ?");
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staffRole = $result->fetch_assoc()['StaffRole'];
    } else {
        return false;
    }

    $stmt->close();

    // Check if the staff role matches the required privilege
    return $staffRole === $requiredPrivilege;
}

// Fetch user details
function getUserDetails($conn, $staffID) {
    $stmt = $conn->prepare("SELECT StaffFname, StaffLname, StaffRole FROM staff WHERE username = ?");
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}



// Get rates content
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

// Handle AJAX requests for rates and pricing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['roomType'])) {
    $roomType = $_GET['roomType'];
    getRatesContent($conn);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['staffFname']) && isset($_POST['staffLname']) && isset($_POST['staffRole']) && isset($_POST['staffEmail']) && isset($_POST['staffConNum']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['security_question']) && isset($_POST['security_answer'])) {
        // Check for existing username
        $username = $_POST['username'];
        $stmt = $conn->prepare("SELECT username FROM staff WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: Username already exists.";
        } else {
            // Check for existing email
            $email = $_POST['staffEmail'];
            $stmt = $conn->prepare("SELECT StaffEmail FROM staff WHERE StaffEmail = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Error: Email address already exists.";
            } else {
                // Add new staff
                $staffFname = $_POST['staffFname'];
                $staffLname = $_POST['staffLname'];
                $staffMI = isset($_POST['staffMI']) ? $_POST['staffMI'] : null;
                $staffRole = $_POST['staffRole'];
                $staffEmail = $_POST['staffEmail'];
                $staffConNum = $_POST['staffConNum'];
                $password = $_POST['password']; // The plain password here
                $security_question = $_POST['security_question'];
                $security_answer = $_POST['security_answer'];

                // Hash the password before storing it
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO staff (StaffFname, StaffLname, StaffMI, StaffRole, StaffEmail, StaffConNum, username, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("ssssssssss", $staffFname, $staffLname, $staffMI, $staffRole, $staffEmail, $staffConNum, $username, $hashedPassword, $security_question, $security_answer);

                // Execute the query
                if ($stmt->execute()) {
                    echo "New staff added successfully";
                } else {
                    echo "Error executing statement: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    } elseif (isset($_POST['roomType']) && isset($_POST['rate']) && isset($_POST['originalRoomType'])) {
        // Update rate
        $roomType = $conn->real_escape_string($_POST['roomType']);
        $rate = $conn->real_escape_string($_POST['rate']);
        $originalRoomType = $conn->real_escape_string($_POST['originalRoomType']);

        $sql = "UPDATE occtype SET OccRate = '$rate' WHERE OccType = '$originalRoomType'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Rate updated successfully.";
        } else {
            echo "Error updating rate: " . $conn->error;
        }
    } elseif (isset($_POST['currentUsername']) && isset($_POST['currentPassword']) && isset($_POST['newUsername']) && isset($_POST['newPassword'])) {
        // Update current user details
        $currentUsername = $_SESSION['staff_id']; // Assuming staff_id is the current username
        $currentPassword = $_POST['currentPassword'];
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];

        // Log the values for debugging
        error_log("Current Username: " . $currentUsername);
        error_log("Current Password: " . $currentPassword);
        error_log("New Username: " . $newUsername);
        error_log("New Password: " . $newPassword);

        // Validate current password
        $stmt = $conn->prepare("SELECT password FROM staff WHERE username = ?");
        $stmt->bind_param("s", $currentUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($currentPassword, $row['password'])) {
                // Hash the new password
                $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the username and password
                $stmt = $conn->prepare("UPDATE staff SET username = ?, password = ? WHERE username = ?");
                $stmt->bind_param("sss", $newUsername, $hashedNewPassword, $currentUsername);

                if ($stmt->execute()) {
                    echo "Username and password updated successfully.";
                    $_SESSION['staff_id'] = $newUsername; // Update the session username
                } else {
                    echo "Error updating username/password: " . $stmt->error;
                    error_log("Update Error: " . $stmt->error);
                }
                $stmt->close();
            } else {
                echo "Current password is incorrect.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Required fields are missing.";
    }
}


?>
