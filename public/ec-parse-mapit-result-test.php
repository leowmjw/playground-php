<?php

include "../vendor/autoload.php";

// Sample JSON below ..
// Use GeoJSON Polygon ..

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

// Centralize the tag in the given lng, lat?
$finalized_html = <<<MYHTML

<!DOCTYPE html>
<html>
<head>
  <title>EC Malaysia!</title>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript" src="//hpneo.github.io/gmaps/gmaps.js"></script>
  <script type="text/javascript" src="//hpneo.github.io/gmaps/prettify/prettify.js"></script>
  <link href='//fonts.googleapis.com/css?family=Convergence|Bitter|Droid+Sans|Ubuntu+Mono' rel='stylesheet' type='text/css' />
  <link href='//hpneo.github.io/gmaps/styles.css' rel='stylesheet' type='text/css' />
  <link href='//hpneo.github.io/gmaps/prettify/prettify.css' rel='stylesheet' type='text/css' />
  <link rel="stylesheet" type="text/css" href="examples.css" />
  <script type="text/javascript">
    var map;
    $(document).ready(function(){
      map = new GMaps({
        div: '#map',
        lat: 3.1488914,
        lng: 101.6130483
      });

      var paths = $coordinates;
      var paths_dun = $coordinates_dun;
      var paths_dm = $coordinates_dm;
      var paths_are = $coordinates_are;

      polygon = map.drawPolygon({
        paths: paths,
        useGeoJSON: true,
        strokeColor: '#BBD8E9',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#BBD8E9',
        fillOpacity: 0.6
      });
        
      polygon_dun = map.drawPolygon({
        paths: paths_dun,
        useGeoJSON: true,
        strokeColor: '#FFD8E9',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#FFD8E9',
        fillOpacity: 0.6
      });

      polygon_dm = map.drawPolygon({
        paths: paths_dm,
        useGeoJSON: true,
        strokeColor: '#88D8E9',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#88D8E9',
        fillOpacity: 0.6
      });
    
        
    });
  </script>
</head>
<body>
  <div>
    <h2>EC Malaysia Checker</h2>
  </div>
  <div id="body">
    <h3>Parliament (PAR) + State Assembly (DUN) + Voting District (DM) Maps</h3>
    <div class="row">
      <div class="span11">
        <div class="popin">
          <div id="map"></div>
        </div>
      </div>
    </div>
    <h3>From EC</h3>
    <div class="row">
      <div class="span11">
        <div class="popin">
          <div id="mapdun">Details</div>
        </div>
      </div>
    </div>
    <h3>From Mapit</h3>
    <div class="row">  
      <div class="span11">
        <div class="popin">
          <div id="mapdm">Details</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>        
MYHTML;

echo $finalized_html;

function extractCoordinates($geojson_output) {
    //var_dump($geojson_output);
    $geojson_array = json_decode($geojson_output, true);
    // die("Ack!");
    return json_encode($geojson_array['coordinates']);
}
