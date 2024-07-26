<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$database = "im2database";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputEmail = $conn->real_escape_string($_POST['email']);
    $inputSecurityAnswer = $conn->real_escape_string($_POST['SecurityAnswer']);
    $newPassword = $conn->real_escape_string($_POST['newPassword']);
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Query to fetch the user and hashed security answer
    $sql = "SELECT StaffEmail, security_answer FROM staff WHERE StaffEmail = '$inputEmail' ";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the provided security answer
        if ($inputSecurityAnswer === $user['security_answer']) {
            // Update the password in the database
            $updateSql = "UPDATE staff SET password = '$hashedPassword' WHERE StaffEmail = '$inputEmail'";
            if ($conn->query($updateSql) === TRUE) {

                $successMessage = "Password updated successfully. You can now log in with your new password.";
                header("Location: ../login/login.php");
        exit();
            } else {
                $errorMessage = "Error updating password: " . $conn->error;
            }
        } else {
            $errorMessage = "Invalid security answer, please try again or contact admin.";
        }
    } else {
        $errorMessage = "No user found with this email, please try again or contact admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munoz Boarding House - Forgot Password</title>
    <link rel="stylesheet" href="styles/login-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Mallanna&display=swap" rel="stylesheet">
    <style>
        /* Add some basic styles for error messages */
        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="icons/logo.png" alt="Munoz Boarding House Logo" class="logo">
            </div>
            <h2>Please Input Your Email, Answer to Your Security Question, and New Password</h2>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <form  method="POST">
                <input type="email" name="email" placeholder="sample@gmail.com" required>
                <input type="text" name="SecurityAnswer" placeholder="Answer" required>
                <input type="password" name="newPassword" placeholder="New Password" required>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
