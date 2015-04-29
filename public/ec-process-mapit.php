<?php

include "../vendor/autoload.php";

// Description: Use Guzzle to download Mapit based on lng, lat
// Process the outcoming Geocode ...
// Find out the parents all the way to the top ...

// Use the javascript given by @sweemeng to tie all the pieces together; stub out all the calls ..


// Data from EC

// Data from Mapit

// Show it side by side for all layers??

// Report if got errors; send message to slack?
// use Slack API ..

$mapit = new \EC\MapIt();

// var_dump($mapit->getDetails());

$mybob = $mapit->getDetails();

// var_dump($mybob);

foreach ($mybob as $key => $value) {
    echo "Key: $key Value: $value  <br/>";
}
//$mysam = print_r($mybob,true);

//nl2br($mysam);
