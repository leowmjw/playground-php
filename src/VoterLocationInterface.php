<?php

namespace EC;

/**
 * Description of VoterLocationInterface
 *
 * @author leow
 */
interface VoterLocationInterface {

    //put your code here
    public function getFullAddress();

    public function getPostcode();

    public function getPossibleAddresses();

    public function getBackupAddresses();

    public function getPossiblePoints();

    public function getPointsLngLat();
}
