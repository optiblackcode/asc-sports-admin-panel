<?php
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'code' => '1000.f88b3a12559781da6ec21c14fa946055.f5d33a575b5c2845d466fbaf081126f2',
        'redirect_uri' => 'http://localhost/zoho_api/redirection.php',
        'client_id' => '1000.PAMWYI3N6J3650287SOFVFZ8J4DLCO',
        'client_secret' => '7aad6fe00dd32173da2a04661ffb418ec0aab30318',
        'grant_type' => 'authorization_code'
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
print_r($resp);


?>