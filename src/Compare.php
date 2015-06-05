<?php

namespace EC;

// use Geocoder\Result\ResultInterface;

/**
 * Description of Compare
 *
 * @author leow
 */
class Compare implements CompareInterface {

    private $geocoder_results = null;
    private $voter = null;

    public function __construct($apiKey, \EC\ECSiteInterface $ec_site, \EC\VoterLocationInterface $voter_location) {
        // TODO: Actually; corectly is to pass in voter interface ..
        // TODO: STub out HtpAdapter and Provider?? to be able to mock in data ?
        if (null === $this->geocoder_results) {
            // Get all the needed for Geocode
            $geocoder = new \Geocoder\Geocoder();
            $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
            $geotools = new \League\Geotools\Geotools();
            // Execute geocode on possible addresses
            $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $apiKey));
            // Testing wth dummy
            if (null === $this->voter) {
                $this->voter = new Voter($ec_site, $voter_location);
            }
            // Real
            // $voter = new EC\Voter(new EC\ECSite($myic), new EC\VoterLocation($postcode, $address));

            try {
                $this->geocoder_results = $geotools->batch($geocoder)->geocode($this->voter->getLocations())->parallel();
                // echo "Number of results: " . count($results) . "<br/><br/>";
                // TODO: count nuebr of results back??
                // If no result; invoke ?? $voter->getBackupLocations();
            } catch (\Exception $ex) {
                // Any provider errors are NOT caught here; encapsulated inside BatchGeocoded already ..
                die("Unknown Exception: " . $ex->getMessage());
            }
        }
    }

    public function __toString() {
        return print_r($this->dooDah($this->geocoder_results), true);
    }

    public function getJSSnippet() {
        
    }

    public function render($display) {
        // INput Data {{ $display['input'] }}
        // EC Data inside voter? {{ $display['output']['voter'] }}
        //  ----> [par]/[ic]..
        // MapIt Data {{ $display['output']['mapit'] }}
        //  ----> [lat]/[lng]
        //  ----> [par][polygon]
        //  ----> [par][name]
        // Input variables ..
        $myic = $display['input']['ic'];
        $mypostcode = $display['input']['postcode'];
        $myaddress = $display['input']['address'];

        $finalized_polygon = $this->renderPolygon($display['output']['mapit']);
        $finalized_ec = $this->renderECDetail($display['output']['voter']);
        $finalized_mapit = $this->renderMapItDetail($display['output']['mapit']);
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
  <!-- START renderPolygon -->
  $finalized_polygon
  <!-- END renderPolygon -->
</head>
<body>
  <div>
    <h2>EC Malaysia Checker</h2>
  </div>
  <div id="body">
    <div>
      <form type="get" action="/compare">
        IC: <input type="text" name="ic" value="$myic" /> 
        Postcode: <input type="text" name="postcode" value="$mypostcode" /> 
        Address: <input type="text" name="address" value="$myaddress"/>
        <input type="submit"/>
      </form>
    </div>                
    <h3>Parliament (PAR) + State Assembly (DUN) + Voting District (DM) Maps</h3>
    <div class="row">
      <div class="span11">
        <div class="popin">
          <div id="map"></div>
        </div>
      </div>
    </div>
  <!-- START renderECDetail -->
  $finalized_ec
  <!-- END renderECDetail -->
  <!-- START renderMapItDetail -->
  $finalized_mapit
  <!-- END renderMapItDetail -->                
  </div>
</body>
</html>        
MYHTML;

        if ($display['error']) {
            return "FAIL!!! Data dump as follows: " . print_r($display, true);
        }

        return $finalized_html;
    }

    public function view() {
        // form template ..
        // Init
        $view = array();
        // Data from Mapit
        $mapit = new \EC\MapIt();
        // Dump out the needed MapIt URLs ..
        foreach ($this->geocoder_results as $result) {
            if ('' == $result->getExceptionMessage()) {
                // Extract out what is needed .. first hit .. probably better way ..
                $mapit->extractMapIt($result);
                // Extract only ..
                // return back ViewModel
                $view['voter'] = $this->voter->getDetails();
                $view['mapit'] = $mapit->getMapItViewModel();
                return $view;
            } else {
                // var_dump($result);
                // echo $result->getExceptionMessage();
            }
        }
    }

    //put your code here
    // Protected
    protected function dooDah($results) {
        // Data from Mapit
        $mapit = new \EC\MapIt();
        // Dump out the needed MapIt URLs ..
        foreach ($results as $result) {
            if ('' == $result->getExceptionMessage()) {
                // Extract out what is needed ..
                return $mapit->extractMapIt($result);
                // Extract only ..
            } else {
                echo $result->getExceptionMessage();
            }
        }
    }

    protected function renderPolygon($mapit_point) {
        // MapIt Data {{ $display['output']['mapit'] }}
        //  ----> [lat]/[lng]
        //  ----> [par][polygon]
        // Loop through the data here ..
        $coordinates_lat = $mapit_point['lat'];
        $coordinates_lng = $mapit_point['lng'];
        $coordinates_par = $mapit_point['par']['polygon'];
        $coordinates_dun = $mapit_point['dun']['polygon'];
        $coordinates_dm = $mapit_point['dm']['polygon'];
        $coordinates_are = $mapit_point['are']['polygon'];
        // Voter Full Address
        $voter_full_address = array_pop($this->voter->getLocations());
        // Use dumb output first ..
        $template = <<<TEMPLATE
  <script type="text/javascript">
                
    // Init variables for map
    var map;
    var polygon;
    var polygon_dun;
    var polygon_dm;
    var polygon_are;
                
    $(document).ready(function(){
      map = new GMaps({
        div: '#map',
        lat: $coordinates_lat,
        lng: $coordinates_lng
      });

      location_marker = map.addMarker({
        lat: $coordinates_lat,
        lng: $coordinates_lng,
        title: 'Voters Location',
        draggable: true,        
        infoWindow: {
          content: '<p>$voter_full_address</p>'
        }
      });
                
      var paths = $coordinates_par;
      var paths_dun = $coordinates_dun;
      var paths_dm = $coordinates_dm;
      var paths_are = $coordinates_are;

      polygon_are = map.drawPolygon({
        paths: paths_are,
        useGeoJSON: true,
        strokeColor: '#ABBB17',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#F3F756',
        fillOpacity: 0.4
      });

      polygon = map.drawPolygon({
        paths: paths,
        useGeoJSON: true,
        strokeColor: '#BBD8E9',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#BBD8E9',
        fillOpacity: 0.5
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
        strokeColor: '#ED2A40',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#F25C6D',
        fillOpacity: 0.7
      });
    
          
   // Bind to the relevant actions here .. using the available polygin_x ..
   $("a#togglepar").click(function() {
     // Toggle visibility behavior ..
     if (polygon.getVisible()) {
       polygon.setVisible(false);
     } else {
       polygon.setVisible(true);         
     }
   }); // END click

   $("a#toggleare").click(function() {
     // Toggle visibility behavior ..
     if (polygon_are.getVisible()) {
       polygon_are.setVisible(false);
     } else {
       polygon_are.setVisible(true);         
     }
   }); // END click_are

   $("a#toggledun").click(function() {
     // Toggle visibility behavior ..
     if (polygon_dun.getVisible()) {
       polygon_dun.setVisible(false);
     } else {
       polygon_dun.setVisible(true);         
     }
   }); // END click_dun

   $("a#toggledm").click(function() {
     // Toggle visibility behavior ..
     if (polygon_dm.getVisible()) {
       polygon_dm.setVisible(false);
     } else {
       polygon_dm.setVisible(true);         
     }
   }); // END click_dm
                
  });  // END onReady
  </script>
        
TEMPLATE;
        return $template;
    }

    protected function renderECDetail($voter_output) {
        // EC Data inside voter? {{ $display['output']['voter'] }}
        //  ----> [par]/[ic]..
        if (empty($voter_output)) {
            $voter_details = "<br/><br/>NO RESUTS!!";
        } else {
            $voter_details = "";
            foreach ($voter_output as $key => $value) {
                $voter_details .= "<br/> $key: $value";
            }
        }
        // Loop through the data here ..
        // Use dumb output first ..
        $template = <<<TEMPLATE
    <h3>From EC</h3>
    <div class="row">
      <div class="span11">
        <div class="popin">
          <div id="mapdun">Details$voter_details</div>
        </div>
      </div>
    </div>
        
TEMPLATE;
        return $template;
    }

    protected function renderMapItDetail($mapit_output) {
        // MapIt Data {{ $display['output']['mapit'] }}
        //  ----> [par][name]
        if (empty($mapit_output)) {
            $mapit_details = "<br/><br/>NO RESUTS!!";
        } else {
            $mapit_details = "";
            foreach ($mapit_output as $type => $value) {
                switch ($type) {
                    case 'url':
                        $mapit_details .= "<br/> URL: " . '<a href="' . $value['name'] . '" >' . $value['name'] . '</a>';
                        break;
                    case 'par':
                        $mapit_details .= "<br/> PAR: " . $value['name'] .
                                ((empty($value['name'])) ? '' : '  <a id="toggle' . $type . '" href="javascript:;">Toggle PAR</a>');
                        break;
                    case 'dun':
                        $mapit_details .= "<br/> DUN: " . $value['name'] .
                                ((empty($value['name'])) ? '' : '  <a id="toggle' . $type . '" href="javascript:;">Toggle DUN</a>');
                        break;
                    case 'dm':
                        $mapit_details .= "<br/> DM: " . $value['name'] .
                                ((empty($value['name'])) ? '' : '  <a id="toggle' . $type . '" href="javascript:;">Toggle DM</a>');
                        break;
                    case 'are':
                        $mapit_details .= "<br/> ARE: " . $value['name'] .
                                ((empty($value['name'])) ? '' : '  <a id="toggle' . $type . '" href="javascript:;">Toggle AREA</a>');
                        break;

                    default:
                        break;
                }
            }
            // Loop through the data here ..
        }
        // Use dumb output first ..
        $template = <<<TEMPLATE
    <h3>From Mapit</h3>
    <div class="row">  
      <div class="span11">
        <div class="popin">
          <div id="mapdm">Details$mapit_details</div>
        </div>
      </div>
    </div>        
TEMPLATE;
        return $template;
    }

}
