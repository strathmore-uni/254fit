<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "254Fit";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$import_google_fit = isset($_POST['import_google_fit']) ? 1 : 0;

$sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id;
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $email;

    if ($import_google_fit) {
        header('Location: fit_login.php');
    } else {
        header('Location: set_goals.php');
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
