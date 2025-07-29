<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "";
$dbname = "rekindle";

$conn = new mysqli($host, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // turn on exceptions

try {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $event = $_POST['event'] ?? '';
    $reason = $_POST['reason'] ?? '';

    // Prepare the statement
    $stmt = $conn->prepare("INSERT INTO registrations (name, email, phone, event, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $event, $reason);

    $stmt->execute();

    // Success: redirect to thank you page
    header("Location: thankyou.html");
    exit();

} catch (mysqli_sql_exception $e) {
    // Check if it's a duplicate entry error (code 1062)
    if ($e->getCode() == 1062) {
        header("Location: duplicate.html");
        exit();
    } else {
        // Other DB error
        echo "Database error: " . $e->getMessage();
    }
} finally {
    $conn->close();
}
?>
