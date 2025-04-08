.<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('571300424648-i1lumjcscj3i593mb9p0q2n8gg2j217h.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/peso1/callback.php'); // change to your callback URL
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
?>
