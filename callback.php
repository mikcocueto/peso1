<?php
require_once 'vendor/autoload.php'; // Google Client
session_start();

// Setup client
$client = new Google_Client();
$client->setClientId('571300424648-i1lumjcscj3i593mb9p0q2n8gg2j217h.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/peso1/callback.php');
$client->addScope('email');
$client->addScope('profile');

// Authenticate
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google_Service_Oauth2($client);
        $userinfo = $oauth2->userinfo->get();

        $email = $userinfo->email;
        $name = $userinfo->name;
        $google_id = $userinfo->id;

        // Now connect to your database
        $conn = new mysqli("localhost", "root", "", "pesodb");

        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM tbl_emp_login WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // User not found – register them
            $insert = $conn->prepare("INSERT INTO tbl_emp_login (name, emailAddress, google_id) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $name, $email, $google_id);
            $insert->execute();
        }

        // Start session
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error logging in with Google.";
    }
} else {
    echo "No code provided.";
}
