<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signup.html');
    exit();
}

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

$user_id = $_SESSION['user_id'];
$calorie_goal = $_POST['calorie_goal'];
$protein_goal = $_POST['protein_goal'];
$carbs_goal = $_POST['carbs_goal'];
$fats_goal = $_POST['fats_goal'];
$weight_goal = $_POST['weight_goal'];

$sql = "INSERT INTO user_goals (user_id, calorie_goal, protein_goal, carbs_goal, fats_goal, weight_goal)
VALUES ('$user_id', '$calorie_goal', '$protein_goal', '$carbs_goal', '$fats_goal', '$weight_goal')";

if ($conn->query($sql) === TRUE) {
    echo "Goals saved successfully";
    // Redirect to dashboard or profile page
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
