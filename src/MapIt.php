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
        // echo $this->dumpMapitUrl($result) . "<br/><br/>";
        $mapit_results = json_decode($adapter->getContent($this->dumpMapitUrl($result)), true);
        // Categorize to the correct buckets ..
        foreach ($mapit_results as $mapit_id => $mapit_result) {
            switch ($mapit_result['type']) {
                case 'PAR':
                    // Extract out PAR
                    $coordinates = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // Fill it into content structure?? from the coordinates section ..
                    echo "PAR: $coordinates <br/><br/>";
                    break;
                case 'DUN':
                    $coordinates_dun = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    echo "DUN: $coordinates_dun <br/><br/>";
                    break;
                case 'DM':
                    $coordinates_dm = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    echo "DM: $coordinates_dm <br/><br/>";
                    break;
                case 'ARE':
                    $coordinates_are = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    echo "ARE: $coordinates_are <br/><br/>";
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

    protected function extractCoordinates($geojson_output) {
        // var_dump($geojson_output);
        $geojson_array = json_decode($geojson_output, true);
        // die("Ack!");
        return json_encode($geojson_array['coordinates']);
    }

}
