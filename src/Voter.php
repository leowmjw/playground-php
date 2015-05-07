<?php

namespace EC;

/**
 * Description of Voter ==> all to do with EC Voter details ..
 *
 * @author leow
 */
class Voter {

    //put your code here
    private $voter_detail_array = null;
    private $voter_locations_array = null;

    public function __construct(\EC\ECSiteInterface $voter_details, \EC\VoterLocationInterface $voter_locations) {
        // Attach details
        if (null === $this->voter_detail_array) {
            // Validation here??
            // Must match patterns?  strip off as per necessary??
            $this->voter_detail_array = $voter_details;
        }
        // Attach locations
        if (null === $this->voter_locations_array) {
            // How to validate
            $this->voter_locations_array = $voter_locations;
        }
    }

    public function __toString() {
        return $this->debugVoterDetails();
    }

    public function getDM() {
        return $this->voter_detail_array['dm'];
    }

    public function getDUN() {
        return $this->voter_detail_array['dun'];
    }

    public function getPAR() {
        return $this->voter_detail_array['par'];
    }

    public function getDetails() {
        return $this->voter_detail_array;
    }

    public function getLocations() {
        return $this->voter_locations_array;
    }

    protected function debugVoterDetails() {
        return print_r($this->voter_detail_array, true);
    }

}
