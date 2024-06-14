<?php
session_start();

$client_id = 'YOUR_CLIENT_ID';
$redirect_uri = 'http://localhost/fit_callback.php';
$scope = 'https://www.googleapis.com/auth/fitness.activity.read https://www.googleapis.com/auth/fitness.body.read';

$auth_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope&access_type=offline";

header('Location: ' . $auth_url);
exit();
?>
