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
    private $voter_backup_locations_array = null;
    private $voter_locations = null;

    public function __construct(\EC\ECSiteInterface $ec_site, \EC\VoterLocationInterface $voter_locations) {
        // Attach details
        if (null === $this->voter_detail_array) {
            $voter_details = $ec_site->getLabels();
            // Validation here??
            // Must match patterns?  strip off as per necessary??
            $this->voter_detail_array = $voter_details;
        }
        // Attach locations
        if (null === $this->voter_locations_array) {
            // How to validate
            $this->voter_locations_array = $voter_locations->getPossibleAddresses();
        }
        // Attach backup locations
        if (null === $this->voter_backup_locations_array) {
            // How to validate
            $this->voter_backup_locations_array = $voter_locations->getBackupAddresses();
        }
        if (null == $this->voter_locations) {
            $this->voter_locations = $voter_locations;
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
    
    public function getBackupLocations() {
        // This is a better way?  rather than attacj the arrays??
        return $this->voter_locations->getBackupAddresses();
    }

    protected function debugVoterDetails() {
        $debug_output = "";
        $debug_output .= "DETAILS: " . print_r($this->voter_detail_array, true) . "\n";
        $debug_output .= "LOCATIONS: " . print_r($this->voter_locations_array, true) . "\n";
        $debug_output .= "BACKUP LOCATIONS: " . print_r($this->voter_backup_locations_array, true) . "\n";
        return $debug_output;
    }

}
