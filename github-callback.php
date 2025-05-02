<?php
$clientID = 'Ov23liTtetwXNvGYQ8yx';
$clientSecret = '2ffea0be608b2a23ebeb2ce6dd6cd20027400325';
$redirectUri = 'https://yourdomain.com/github-callback.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Step 1: Exchange code for access token
    $url = 'https://github.com/login/oauth/access_token';
    $data = [
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'code' => $code,
        'redirect_uri' => $redirectUri
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        // Step 2: Get user data
        $userResponse = file_get_contents("https://api.github.com/user", false, stream_context_create([
            'http' => [
                'header' => "User-Agent: YourApp\r\nAuthorization: Bearer $accessToken\r\n"
            ]
        ]));

        $user = json_decode($userResponse, true);

        echo "<pre>";
        print_r($user); // Or redirect to your app dashboard
        echo "</pre>";
    } else {
        echo "Error fetching access token.";
    }
} else {
    echo "Authorization code not found.";
}
