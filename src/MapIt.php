<?php

namespace EC;

use \Geocoder\Result\ResultInterface;

/**
 * Description of MapIt
 *
 * @author leow
 */
class MapIt implements MapItInterface {

    public function getMapItPoint(\Geocoder\Result\ResultInterface $result) {
        // Initialized the needed
        $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $mapit_results = json_decode($adapter->getContent(dumpMapitUrl($result)), true);
        // Categorize to the correct buckets ..
        foreach ($mapit_results as $mapit_id => $mapit_result) {
            switch ($mapit_result['type']) {
                case 'PAR':
                    // Extract out PAR
                    $coordinates = extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // Fill it into content structure?? from the coordinates section ..
                    break;
                case 'DUN':
                    $coordinates_dun = extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;
                case 'DM':
                    $coordinates_dm = extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;
                case 'ARE':
                    $coordinates_are = extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;

                default:
                    break;
            }
        }
    }

    public function getDMPolygons() {
        
    }

    public function getDUNPolygons() {
        
    }

    public function getPARPolygons() {
        
    }

    // Protected items ..
    protected function dumpMapitUrl(ResultInterface $result) {
        $sinar_mapit_base_url = "http://mapit.sinarproject.org/point/4326/";
        return $sinar_mapit_base_url . $result->getLongitude() . "," . $result->getLatitude();
    }

}
