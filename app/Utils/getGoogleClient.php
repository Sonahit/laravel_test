<?php

function getGoogleClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Youtube');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig(app_path('credentials.json'));
    // $client->setClientId(env('GOOGLE_CLIENT_ID'));
    // $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setAccessType('online');
    $client->setApprovalPrompt('auto');
    $client->useApplicationDefaultCredentials();


    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = app_path('token.json');
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }
    return $client;
}
