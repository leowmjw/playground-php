<?php

namespace EC;

/**
 * Description of VoterLocation
 *
 * @author leow
 */
class VoterLocation implements VoterLocationInterface {

    private $geocoder = null;
    private $postcode = null;
    private $address = null;
    private $possible_addresses = array();

    public function __construct(\Geocoder\GeocoderInterface $geocoder, $postcode, $address = null) {
        $this->geocoder = $geocoder;
        $this->postcode = $postcode;
        $this->address = $address;
        $this->possible_addresses[] = $this->postcode;
        if (!empty($this->address)) {
            $this->possible_addresses[] = $this->address . (($this->postcode) ? ", " . $this->postcode : '');
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
        $possible_addresses = array();
        if (!empty($this->postcode)) {
            $possible_addresses[] = $this->postcode;
        }
        if (!empty($this->address)) {
            $possible_addresses[] = $this->address . (($this->postcode) ? ", " . $this->postcode : '');
        }
        return $possible_addresses;
    }

    public function getPossiblePoints() {
        
    }

    public function getPointsLngLat() {
        
    }
    // Protected items ..
    protected function dumpMapitUrl(ResultInterface $result) {
        $sinar_mapit_base_url = "http://mapit.sinarproject.org/point/4326/";
        return $sinar_mapit_base_url . $result->getLongitude() . "," . $result->getLatitude();
    }

}
