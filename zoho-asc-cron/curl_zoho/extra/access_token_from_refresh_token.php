<?php
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'refresh_token' => '1000.7448f0d531464ecdb01aa1bd8769f230.c9065bc39a77407636c66b373dccfa79',
        'client_id' => '1000.PAMWYI3N6J3650287SOFVFZ8J4DLCO',
        'client_secret' => '7aad6fe00dd32173da2a04661ffb418ec0aab30318',
        'grant_type' => 'refresh_token'
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
print_r($resp);


?>