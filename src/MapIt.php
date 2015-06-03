<?php

namespace EC;

use \Geocoder\Result\ResultInterface;

/**
 * Description of MapIt
 *
 * @author leow
 */
class MapIt implements MapItInterface {

    private $mapit_view_model = null;

    public function getMapItViewModel($output_json = false) {
        if ($output_json) {
            return json_encode($this->mapit_view_model);
        }
        return $this->mapit_view_model;
    }

    public function extractMapIt(ResultInterface $result) {
        // fill in the points for latlng?
        $this->mapit_view_model['lat'] = $result->getLatitude();
        $this->mapit_view_model['lng'] = $result->getLongitude();
        // Extract and set ploygin and name etc .. / search key
        $this->extractMapItPoint($result);
        $this->extractMapItInfo($result);
    }

    public function getDMPolygons() {
        return $this->mapit_view_model['dm']['polygon'];
    }

    public function getDUNPolygons() {
        return $this->mapit_view_model['dun']['polygon'];
    }

    public function getPARPolygons() {
        return $this->mapit_view_model['par']['polygon'];
    }

    public function __toString() {
        // Output should be in the form of .. ???
        // Use array set too??
        return $this->debugMapIt();
    }

    // Protected items ..
    protected function extractMapItPoint(ResultInterface $result) {

        // Initialized the needed
        $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        // Initialized all the needed; if no data is returned, leave it as an empty list
        $this->mapit_view_model['par']['polygon'] = "[]";
        $this->mapit_view_model['dun']['polygon'] = "[]";
        $this->mapit_view_model['dm']['polygon'] = "[]";
        $this->mapit_view_model['are']['polygon'] = "[]";

        // echo $this->dumpMapitUrl($result) . "<br/><br/>";
        $mapit_results = json_decode($adapter->getContent($this->dumpMapitUrl($result)), true);
        // Categorize to the correct buckets ..
        foreach ($mapit_results as $mapit_id => $mapit_result) {
            // Init
            $coordinates = null;
            switch ($mapit_result['type']) {
                case 'PAR':
                    // Extract out PAR
                    // echo print_r($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    $coordinates = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // Fill it into content structure?? from the coordinates section ..
                    // echo "PAR: $coordinates <br/><br/>";
                    $this->mapit_view_model['par']['polygon'] = $coordinates;
                    break;
                case 'DUN':
                    $coordinates = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // echo "DUN: $coordinates_dun <br/><br/>";
                    $this->mapit_view_model['dun']['polygon'] = $coordinates;
                    break;
                case 'DM':
                    $coordinates = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // echo "DM: $coordinates_dm <br/><br/>";
                    $this->mapit_view_model['dm']['polygon'] = $coordinates;
                    break;
                case 'ARE':
                    $coordinates = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // echo "ARE: $coordinates_are <br/><br/>";
                    $this->mapit_view_model['are']['polygon'] = $coordinates;
                    break;

                default:
                    break;
            }
        }
    }

    protected function extractMapItInfo(ResultInterface $result) {

        // Initialized the needed
        $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        // echo $this->dumpMapitUrl($result) . "<br/><br/>";
        $mapit_results = json_decode($adapter->getContent($this->dumpMapitUrl($result)), true);
        // Categorize to the correct buckets ..
        foreach ($mapit_results as $mapit_id => $mapit_result) {
            // Init
            $name = null;
            switch ($mapit_result['type']) {
                case 'PAR':
                    // Extract out PAR
                    // echo print_r($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    $name = $this->extractInfo($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    // Fill it into content structure?? from the coordinates section ..
                    // echo "PAR: $coordinates <br/><br/>";
                    $this->mapit_view_model['par']['name'] = $name;
                    break;
                case 'DUN':
                    $name = $this->extractInfo($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    // echo "DUN: $coordinates_dun <br/><br/>";
                    $this->mapit_view_model['dun']['name'] = $name;
                    break;
                case 'DM':
                    $name = $this->extractInfo($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    // echo "DM: $coordinates_dm <br/><br/>";
                    $this->mapit_view_model['dm']['name'] = $name;
                    break;
                case 'ARE':
                    $name = $this->extractInfo($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id"));
                    // echo "ARE: $coordinates_are <br/><br/>";
                    $this->mapit_view_model['are']['name'] = $name;
                    break;

                default:
                    break;
            }
        }
    }

    protected function dumpMapitUrl(ResultInterface $result) {
        $sinar_mapit_base_url = "http://mapit.sinarproject.org/point/4326/";
        $sinar_mapit_url = $sinar_mapit_base_url . $result->getLongitude() . "," . $result->getLatitude();
        // For debugging purposes; SEO too??
        $this->mapit_view_model['url']['name'] = $sinar_mapit_url;
        return $sinar_mapit_url;
    }

    protected function extractCoordinates($geojson_output) {
        // var_dump($geojson_output);
        $geojson_array = json_decode($geojson_output, true);
        // die("Ack!");
        return json_encode($geojson_array['coordinates']);
    }

    protected function extractInfo($geojson_output) {
        // var_dump($geojson_output);
        $geojson_array = json_decode($geojson_output, true);
        // die("Ack!");
        return json_encode($geojson_array['name']);
    }

    protected function debugMapIt() {
        $debug_output = print_r($this->mapit_view_model, true);
        return nl2br($debug_output);
    }

}
