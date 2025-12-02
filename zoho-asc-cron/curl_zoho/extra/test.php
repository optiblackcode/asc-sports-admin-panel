<?php
$curl = curl_init();
// Set some options - we are passing in a useragent too here
$request_headers = array();
$request_headers[] = 'User-Agent: Mozilla';
$request_headers[] = 'Authorization: Zoho-oauthtoken 1000.f7a633a5ab7f9a48bd3f56aff24a3fa9.f3ee812d65e6bba2be8496dcd6b886a9';
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/Suburb',
    CURLOPT_POST => 0,
    CURLOPT_HTTPHEADER=> $request_headers
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
print_r($resp);


?>