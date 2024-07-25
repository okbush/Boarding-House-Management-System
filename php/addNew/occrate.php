<?php
include '../mysql.php';

$occtype = $_GET['occtype']; // Retrieve Occtype from GET parameter

$sql = "SELECT OccRate FROM occtype WHERE Occtype = '$occtype'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $occRate = $row['OccRate'];

    // Return OccRate as JSON response
    echo json_encode(['success' => true, 'occRate' => $occRate]);
} else {
    // If no result found, return error message
    echo json_encode(['success' => false, 'error' => 'No OccRate found for ' . $occtype]);
}

$conn->close();
?>
