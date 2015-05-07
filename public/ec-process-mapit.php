<?php

include "../vendor/autoload.php";

// Description: Use Guzzle to download Mapit based on lng, lat
// Process the outcoming Geocode ...
// Find out the parents all the way to the top ...
// Use the javascript given by @sweemeng to tie all the pieces together; stub out all the calls ..
// Use ENV for test ICs ..
$env_loader = new \josegonzalez\Dotenv\Loader(__DIR__ . "/../env");
$parsed_env = $env_loader->parse();
$parsed_env->toEnv();

// Fill up the polygon; how??
// Init keys
$apiKey = getenv('GOOGLE_API_KEY');
// Get all the needed for Geocode
$geocoder = new \Geocoder\Geocoder();
$adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
$geotools = new \League\Geotools\Geotools();
// Execute geocode on possible addresses
$geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $apiKey));

$mypostcode = $_ENV['TEST_USER1_POSTCODE'];
$myaddress = $_ENV['TEST_USER1_POSTCODE'];
$myic = $_ENV['TEST_USER1_IC'];
$voter = new EC\Voter(new EC\ECSite($myic), new EC\VoterLocation($geocoder, $mypostcode, $myaddress));

// Data from Mapit
$mapit = new EC\MapIt();
// var_dump($mapit->getDetails());
$mybob = $mapit->getDetails();
// var_dump($mybob);
foreach ($mybob as $key => $value) {
    echo "Key: $key Value: $value  <br/>";
}
//$mysam = print_r($mybob,true);
//nl2br($mysam);
// Data from EC

try {
    $results = $geotools->batch($geocoder)->geocode($voter->getLocations())->parallel();
} catch (Exception $ex) {
    die($ex->getMessage());
}
// Dump out the needed MapIt URLs ..
foreach ($results as $result) {
    if ('' == $result->getExceptionMessage()) {
        // Execute JSON for the point
        $mapit->getMapItPoint($result);
    } else {
        echo $result->getExceptionMessage();
    }
}

// Show it side by side for all layers??

// Report if got errors; send message to slack?
// use Slack API ..

