<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Mallanna&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="settings-styles.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="script.js"></script>

    <!-- < ?php include 'process.php'; ?> -->

</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../icons/logo.png" alt="Munoz Boarding House Logo">
        </div>
        <ul class="menu">
            <li><a href="../dashboard.php"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="../tenants.php"><i class="fa fa-address-book-o" aria-hidden="true"></i> Tenants</a></li>
            <li><a href="../roomLogs/index.php"><i class="fa fa-bed" aria-hidden="true"></i> Rooms</a></li>
            <li><a href="../billing.php"><i class="fa fa-credit-card" aria-hidden="true"></i> Billings</a></li>
            <li><a href="settings.php" class="active"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>

    </div>

    <div class="content">
        <div class="main-content">
            <div class="header">
                <h1><b>Settings</b></h1>
                <p>View and manage settings related to your business.</p>

                <div class="profile-info">
                    <div class="info">
                        <h2><?php echo htmlspecialchars($_SESSION['staff_name']) ?></h2>
                        <p>Role: <?php echo htmlspecialchars($_SESSION['staff_role']); ?></p>
                    </div>
                </div>
            </div>
            <div class="settings-content">

                <button type="button" class="rates">Rates and Pricing</button>

                <button type="button" class="add-staff-button">Add New Staff</button>


                <div class="buttons">

                    <button type="button" class="Change-details">Change Details</button>
                </div>

                <h3><a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a></h3>

                <!-- rates modal -->
                <div id="ratesModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div id="modal-body"></div>
                    </div>
                </div>

                <!-- Add Staff Modal -->
                <div id="addStaffModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div id="modal-body"></div>
                    </div>
                </div>
                <!-- details modal -->
                <div id="userdetails" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div id="modal-body">
                            <!-- Form to Change Username and Password -->
                            <form id="changeDetailsForm">
                                <label for="newUsername">New Username:</label><br>
                                <input type="text" id="newUsername" name="newUsername" required
                                    style="width: 100%;">

                                <label for="newPassword">New Password:</label><br>
                                <input type="password" id="newPassword" name="newPassword" required
                                    style="width: 100%;">

                                <button type="submit">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal Structure -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <button Type='button' class="close">&times;</button>
                        <h2>Edit Rate</h2>
                        <form id="editForm">
                            <label for="editRate">Edit Room Rate:</label>
                            <input type="text" id="editRate" name="rate">
                            <input type="hidden" id="originalRoomType" name="originalRoomType">

                            <button type="submit">Save Changes</button>
                        </form>
                    </div>
                </div>
                <!-- Add Staff Form -->
                <div id="addStaffform" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div id="modal-body">
                            <form id="addStaffForm" method="POST" action="your_php_script.php">
                                <label for="staffFname">First Name:</label><br>
                                <input type="text" id="staffFname" name="staffFname" required style="width: 100%;"><br>

                                <label for="staffLname">Last Name:</label><br>
                                <input type="text" id="staffLname" name="staffLname" required style="width: 100%;"><br>

                                <label for="staffMI">Middle Initial:</label><br>
                                <input type="text" id="staffMI" name="staffMI" style="width: 100%;"><br>

                                <label for="staffRole">Role:</label><br>
                                <input type="text" id="staffRole" name="staffRole" required style="width: 100%;"><br>

                                <label for="staffEmail">Email:</label><br>
                                <input type="email" id="staffEmail" name="staffEmail" required style="width: 100%;"><br>

                                <label for="staffConNum">Contact Number:</label><br>
                                <input type="text" id="staffConNum" name="staffConNum" style="width: 100%;"><br>

                                <label for="username">Username:</label><br>
                                <input type="text" id="username" name="username" required style="width: 100%;"><br>

                                <label for="password">Password:</label><br>
                                <input type="password" id="password" name="password" required style="width: 100%;"><br>

                                <label for="security_question">Security Question:</label><br>
                                <input type="text" id="security_question" name="security_question" required
                                    style="width: 100%;"><br>

                                <label for="security_answer">Security Answer:</label><br>
                                <input type="text" id="security_answer" name="security_answer" required
                                    style="width: 100%;"><br>

                                <button type="submit">Add Staff</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="side-settings">
                    <!-- Additional settings content here -->
                </div>
            </div>
        </div>
</body>

</html>