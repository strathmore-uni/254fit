<?php
session_start();

$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';
$redirect_uri = 'http://localhost/fit_callback.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange authorization code for access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $post_fields = [
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);
    $access_token = $token_data['access_token'];
    $refresh_token = $token_data['refresh_token'];

    // Save tokens to session or database
    $_SESSION['access_token'] = $access_token;
    $_SESSION['refresh_token'] = $refresh_token;

    header('Location: fit_data.php');
    exit();
} else {
    echo 'Error: Authorization code not received.';
}
?>
