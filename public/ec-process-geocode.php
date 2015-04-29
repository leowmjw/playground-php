<?php

include "../vendor/autoload.php";

use \Geocoder\Result\ResultInterface;

// Use ENV for apiKey, test address
$env_loader = new \josegonzalez\Dotenv\Loader(__DIR__ . "/../env");
$parsed_env = $env_loader->parse();
$parsed_env->toEnv();
// print_r($_ENV);

// Description: Reversed based on postcode; use google
// ALternative; add address; and give the multiple available choices back??
// as a json coder??
// Use ENV to supply the private keys??
// Restrict to MY and test with postcode 47800 ..
// locale = en; region = my

$geocoder = new \Geocoder\Geocoder();
$adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();

// To be loaded in via configurations??
$apiKey = getenv('GOOGLE_API_KEY');
// echo "API Key is $apiKey";
$geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $apiKey));

$geotools = new \League\Geotools\Geotools();
$dumper = new \Geocoder\Dumper\GeoJsonDumper();

$mypostcode = $_ENV['TEST_USER1_POSTCODE'];
echo "Scenario #1: Postcode $mypostcode <br/><br/>";

try {
    $results = $geotools->batch($geocoder)->geocode($mypostcode)->parallel();
} catch (Exception $ex) {
    die($ex->getMessage());
}

foreach ($results as $result) {
    if ('' == $result->getExceptionMessage()) {
        // "Lng: " . $result->getLongitude() . " Lat: " . $result->getLatitude();
        // echo nl2br($dumper->dump($result));
        echo "URL is: " . dumpMapitUrl($result) . "<br/>";
    } else {
        echo $result->getExceptionMessage();
    }
}

$myaddress = $_ENV['TEST_USER1_ADDRESS'];
echo "<br/><br/>";
echo "Scenario #2: $myaddress <br/><br/>";

try {
    $results = $geotools->batch($geocoder)->geocode($myaddress)->parallel();
} catch (Exception $ex) {
    die($ex->getMessage());
}

foreach ($results as $result) {
    if ('' == $result->getExceptionMessage()) {
        // "Lng: " . $result->getLongitude() . " Lat: " . $result->getLatitude();
        // echo nl2br($dumper->dump($result));
        echo "URL is: " . dumpMapitUrl($result) . "<br/>";
    } else {
        echo $result->getExceptionMessage();
    }
}

function dumpMapitUrl(ResultInterface $result) {
    $properties = array_filter($result->toArray(), function ($val) {
        return $val !== null;
    });
    
    // print_r($properties);
    /*
    $mybob = print_r($properties,true);
    
    nl2br($mybob);
     * 
     */
    
    // Sample point for SUbang Jaya --> http://mapit.sinarproject.org/point/4326/101.6429326,3.0198235.html
    // JSON: http://mapit.sinarproject.org/point/4326/101.6429326,3.0198235
    // <MapitURL>/<lng>,<lat>
    $sinar_mapit_base_url =  "http://mapit.sinarproject.org/point/4326/";
    return $sinar_mapit_base_url . $properties['longitude'] . "," . $properties['latitude'];
}
