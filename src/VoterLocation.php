<?php

namespace EC;

/**
 * Description of VoterLocation
 *
 * @author leow
 */
class VoterLocation implements VoterLocationInterface {

    private $postcode = null;
    private $address = null;
    private $possible_addresses = array();
    private $backup_addresses = array();

    public function __construct($postcode, $address = null) {
        // Postcode must be number only ..!
        $this->postcode = preg_replace("#[^0-9]#", "", $postcode);
        $this->address = $address;
        if (!empty($this->address)) {
            $this->backup_addresses[] = $this->postcode;
            $this->possible_addresses[] = $this->address . (($this->postcode) ? ", " . $this->postcode : '');
        } else {
            // For simplification; just choose as specific ..
            // leave the other to backup address ..
            if (!empty($postcode)) {
                $this->possible_addresses[] = $this->postcode;
            }
        }
    }

    public function getFullAddress() {
        if (!empty($this->address)) {
            return $this->address . (($this->postcode) ? ", " . $this->postcode : '');
        }
        // By defsult; it is postcode!
        return $this->postcode;
    }

    public function getPostcode() {
        return $this->postcode;
    }

    public function getPossibleAddresses() {
        return $this->possible_addresses;
    }

    public function getBackupAddresses() {
        return $this->backup_addresses;
    }

    public function getPossiblePoints() {
        
    }

    public function getPointsLngLat() {
        
    }

}
