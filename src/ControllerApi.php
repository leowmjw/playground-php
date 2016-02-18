<?php
/**
 * Created by PhpStorm.
 * User: leow
 * Date: 2/4/16
 * Time: 10:32 PM
 */

namespace EC;

/**
 * Class ControllerApi
 * @package EC
 */
class ControllerApi
{
    /**
     * @var
     */
    private $token;

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * ControllerApi constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public final function getResult()
    {
        return $this->getBreakdown();
    }

    private function getBreakdown()
    {
        // Get this from initial call to the location ..
        // can filter on particular items;
        $geojson_output = <<< MYGEOJSON

{
    "6801": {
        "parent_area": null,
        "generation_high": 1,
        "all_names": {},
        "id": 6801,
        "codes": {},
        "name": "BANDAR UTAMA BU 3 - BU 8",
        "country": "M",
        "type_name": "Daerah Mengundi",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "DM"
    },
    "59": {
        "parent_area": 52,
        "generation_high": 1,
        "all_names": {},
        "id": 59,
        "codes": {
            "code": "PJU 6"
        },
        "name": "Bandar Utama, Kg Kayu Ara",
        "country": "M",
        "type_name": "Area",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "ARE"
    },
    "1": {
        "parent_area": null,
        "generation_high": 1,
        "all_names": {},
        "id": 1,
        "codes": {},
        "name": "Malaysia",
        "country": "M",
        "type_name": "Country",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "CTR"
    },
    "7164": {
        "parent_area": null,
        "generation_high": 1,
        "all_names": {},
        "id": 7164,
        "codes": {},
        "name": "N37",
        "country": "M",
        "type_name": "Dewan Rakyat",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "DUN"
    },
    "7206": {
        "parent_area": null,
        "generation_high": 1,
        "all_names": {},
        "id": 7206,
        "codes": {},
        "name": "P107",
        "country": "M",
        "type_name": "Parliament",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "PAR"
    },
    "13": {
        "parent_area": 1,
        "generation_high": 1,
        "all_names": {},
        "id": 13,
        "codes": {},
        "name": "Selangor",
        "country": "M",
        "type_name": "State",
        "generation_low": 1,
        "country_name": "Malaysia",
        "type": "STT"
    }
}

MYGEOJSON;

        // Init
        $coordinates = "";
        $coordinates_par = "";
        $coordinates_dun = "";
        $coordinates_dm = "";
        $coordinates_are = "";

        // decode_the JSON ..
        $mapit_results = json_decode($geojson_output, true);

        $finalized_html = '';
        // Initialized the needed
        $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        // Categorize to the correct buckets ..
        foreach ($mapit_results as $mapit_id => $mapit_result) {
            switch ($mapit_result['type']) {
                case 'PAR':
                    // Extract out PAR
                    //$mypar = "http://mapit.sinarproject.org/area/$mapit_id.geojson";
                    //print_r($mypar);
                    $coordinates_par = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    // Fill it into content structure?? from the coordinates section ..
                    break;
                case 'DUN':
                    $coordinates_dun = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;
                case 'DM':
                    $coordinates_dm = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;
                case 'ARE':
                    $coordinates_are = $this->extractCoordinates($adapter->getContent("http://mapit.sinarproject.org/area/$mapit_id.geojson"));
                    break;

                default:
                    break;
            }
        }

        // Fill er up ..
        $coordinates['par'] = $coordinates_par;
        $coordinates['dun'] = $coordinates_dun;
        $coordinates['dm'] = $coordinates_dm;
        $coordinates['are'] = $coordinates_are;

        return json_encode($coordinates);

    }

    private function extractCoordinates($geojson_output)
    {
        //var_dump($geojson_output);
        $geojson_array = json_decode($geojson_output, true);
        // die("Ack!");
        if (isset($geojson_array['coordinates'])) {
            return json_encode($geojson_array['coordinates']);
        }
        // Anything happens; return an empty JSON list ..
        return "{}";
    }

    private function getLocation($postcode, $address = null) {

    }

    private function getMapItDetails($location) {

    }

    private function getECDetails($ic_number) {

    }

}