<?php

namespace EC;

/**
 * Description of ControllerPostcodeMapIt
 *
 * @author leow
 */
class ControllerPostcodeMapIt {

    private $key_api;
    private $postcode; // Postcode you can get from voter_location ..
    private $voter_location;
    private $display = array();
    private $geocoder_results;
    private $view_model = array();

    public function __construct($key_api, $postcode) {
        if (null === $this->key_api) {
            $this->key_api = $key_api;
        }

        // Store input for reuse 
        $this->display['input']['postcode'] = htmlentities($postcode);
        if (null == $this->postcode) {
            // Can use the dummy value .. yes!            
            // $voter_location = new \EC\VoterLocationDummy($this->postcode, "");
            $voter_location = new \EC\VoterLocation($postcode, "");
            // validate postcode by trying to create a valid voter_location
            if (!empty($voter_location->getFullAddress())) {
                $this->voter_location = $voter_location;
                $this->geoCodeLookupLocation();
            } else {
                // Error happened; note it down ..
                $this->display['error'] = true;
                $this->display['output']['error']['message'] = "VALIDATION FAILED!!!";
                // Mark in UI structure
                $this->display['input']['error']['postcode'] = "Invalid postcode!!";
            }
        }
    }

    public function __toString() {
        // print what out 
        $this->processData();
        return json_encode($this->view_model);
    }

    public function render() {
        // process Data
        // render needed components ..
        // Build up template; send it back home ..
        // if error; use different template?
    }

    // Protected FUnctions ..
    protected function geoCodeLookupLocation() {
        if (null === $this->geocoder_results) {
            // Get all the needed for Geocode
            $geocoder = new \Geocoder\Geocoder();
            $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
            $geotools = new \League\Geotools\Geotools();
            // Execute geocode on possible addresses
            $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $key_api));
            try {
                $this->geocoder_results = $geotools->batch($geocoder)->geocode($this->voter_location->getPossibleAddresses())->parallel();
                // echo "Number of results: " . count($results) . "<br/><br/>";
                // TODO: count nuebr of results back??
                // If no result; invoke ?? $voter->getBackupLocations();
            } catch (\Exception $ex) {
                $this->display['error'] = true;
                $this->display['output']['error']['message'] = "UNKNOWN EXCEPTION: " . $ex->getMessage();
            }
        }
    }

    protected function processData() {
        if (!empty($this->geocoder_results)) {
            // Only do something if there are geocoder results ..
            // Data from Mapit
            $mapit = new \EC\MapIt();
            // Dump out the needed MapIt URLs ..
            foreach ($this->geocoder_results as $result) {
                if ('' == $result->getExceptionMessage()) {
                    // Extract out what is needed .. first hit .. probably better way ..
                    $mapit->extractMapIt($result);
                    // Extract only ..
                    $this->view_model = $mapit->getMapItViewModel();
                } else {
                    $this->display['error'] = true;
                    $this->display['output']['error']['message'] = "UNKNOWN EXCEPTION: " . $result->getExceptionMessage();
                }
            }
        }
    }

    protected function renderTemplate() {
        
    }

    protected function renderErrorContent() {
        
    }

    protected function renderMapItContent() {
        
    }

    protected function renderPolygon($mapit_point) {
        
    }

    protected function renderMapItDetail($mapit_output) {
        
    }

}
