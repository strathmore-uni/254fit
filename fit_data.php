<?php
session_start();

if (!isset($_SESSION['access_token']) || !isset($_SESSION['user_id'])) {
    header('Location: fit_login.php');
    exit();
}

$access_token = $_SESSION['access_token'];
$user_id = $_SESSION['user_id'];

function getGoogleFitData($access_token, $dataset) {
    $url = "https://www.googleapis.com/fitness/v1/users/me/dataset:aggregate";
    
    $data = [
        "aggregateBy" => [
            [
                "dataTypeName" => $dataset
            ]
        ],
        "bucketByTime" => [
            "durationMillis" => 86400000 // 1 day
        ],
        "startTimeMillis" => strtotime('-1 week') * 1000,
        "endTimeMillis" => time() * 1000
    ];

    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch data for different datasets
$datasets = [
    'com.google.weight' => 'weight',
    'com.google.height' => 'height',
    'com.google.age' => 'age',
    'com.google.gender' => 'gender',
    'com.google.step_count.delta' => 'steps',
    'com.google.activity.segment' => 'activity',
    'com.google.calories.expended' => 'total_energy_expended'
];

$data = [];
foreach ($datasets as $dataset => $name) {
    $data[$name] = getGoogleFitData($access_token, $dataset);
}

// Save data to database
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

$weight = isset($data['weight']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal']) ? $data['weight']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal'] : NULL;
$height = isset($data['height']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal']) ? $data['height']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal'] : NULL;
$age = isset($data['age']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal']) ? $data['age']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal'] : NULL;
$gender = isset($data['gender']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['stringVal']) ? $data['gender']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['stringVal'] : NULL;
$steps = isset($data['steps']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['intVal']) ? $data['steps']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['intVal'] : NULL;
$activity = isset($data['activity']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['stringVal']) ? $data['activity']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['stringVal'] : NULL;
$totalEnergyExpended = isset($data['total_energy_expended']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal']) ? $data['total_energy_expended']['bucket'][0]['dataset'][0]['point'][0]['value'][0]['fpVal'] : NULL;

$sql = "INSERT INTO user_data (user_id, weight, height, age, gender, steps, activity, total_energy_expended)
VALUES ('$user_id', '$weight', '$height', '$age', '$gender', '$steps', '$activity', '$totalEnergyExpended')";

if ($conn->query($sql) === TRUE) {
    echo "Data imported successfully";
    header('Location: set_goals.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
