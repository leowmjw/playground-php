<?php

namespace EC;

// use Geocoder\Result\ResultInterface;

/**
 * Description of Compare
 *
 * @author leow
 */
class Compare implements CompareInterface {

    private $geocoder_results = null;

    public function __construct($apiKey, EC\ECSiteInterface $ec_site, EC\VoterLocationInterface $voter_location, $ic, $postcode, $address = null) {
        // TODO: Actually; corectly is to pass in voter interface ..
        // TODO: STub out HtpAdapter and Provider?? to be able to mock in data ?
        if (null === $this->geocoder_results) {
            // Get all the needed for Geocode
            $geocoder = new \Geocoder\Geocoder();
            $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
            $geotools = new \League\Geotools\Geotools();
            // Execute geocode on possible addresses
            $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $apiKey));
            // Testing wth dummy
            $voter = new EC\Voter(new EC\ECSiteDummy($ic), new EC\VoterLocation($postcode, $address));
            // Real
            // $voter = new EC\Voter(new EC\ECSite($myic), new EC\VoterLocation($postcode, $address));

            try {
                $this->geocoder_results = $geotools->batch($geocoder)->geocode($voter->getLocations())->parallel();
                // echo "Number of results: " . count($results) . "<br/><br/>";
                // TODO: count nuebr of results back??
                // If no result; invoke ?? $voter->getBackupLocations();
            } catch (Exception $ex) {
                die($ex->getMessage());
            }
        }
    }

    public function getJSSnippet() {
        
    }

    public function view() {
        // Points look like:
        // ====================
        // Location #2
        // ====================
        // Array[0][lat] ==> center/point
        // Array[0][lng] ==> center/point
        // Array[0][par][name]
        // Array[0][par][polygon]
        // Array[0][dun][name]
        // Array[0][dun][polygon]
        // Array[0][dm][name]
        // Array[0][dm][polygon]
        // Array[0][are][name]
        // Array[0][are][polygon]
        // ====================
        // Location #2
        // ====================
        // Array[1][point] ==> center/point
        // Array[1][par_polygon]
        // Array[1][dun_polygon]
        // Array[1][dm_polygon]
        // Array[1][are_polygon]
        // Array[2] ..
        // Data from Mapit
        $mapit = new EC\MapIt();
        $this->dooDah($this->geocoder_results, $mapit);
    }

//put your code here
    // Protected
    protected function dooDah($results, $mapit) {
        // Dump out the needed MapIt URLs ..
        foreach ($results as $result) {
            if ('' == $result->getExceptionMessage()) {
                // Extract out what is needed ..
                $mapit->extractMapIt($result);
                // Output structure
                echo $mapit;
            } else {
                echo $result->getExceptionMessage();
            }
        }
    }

}
