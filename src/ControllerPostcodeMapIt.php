<?php

namespace EC;

/**
 * Description of ControllerPostcodeMapIt
 *
 * @author leow
 */
class ControllerPostcodeMapIt {

    private $key_api;
    private $voter_location;
    private $display = array();
    private $geocoder_results;
    private $view_model = array();

    public function __construct($key_api, $postcode) {
        if (null === $this->key_api) {
            $this->key_api = $key_api;
        }

        // If no postcode passed in; we can assume it is the default page .. 
        // nothing to do further
        if (empty($postcode)) {
            return;
        }
        // Store input for reuse 
        $this->display['input']['postcode'] = htmlentities($postcode);
        if (null == $this->postcode) {
            // $voter_location = new \EC\VoterLocationDummy($this->postcode, "");
            $voter_location = new \EC\VoterLocation($postcode, "");
            // validate postcode by trying to create a valid voter_location
            if (!empty($voter_location->getFullAddress())) {
                $this->voter_location = $voter_location;
                $this->geoCodeLookupLocation();
            } else {
                // Error happened; note it down ..
                $this->display['error'] = true;
                $this->display['output']['error']['message'] = "VALIDATION FAILED!!!";
                // Mark in UI structure
                $this->display['input']['error']['postcode'] = "Invalid postcode!!";
            }
        }
    }

    public function __toString() {
        // print what out 
        $this->processData();
        return json_encode($this->view_model);
    }

    public function render() {
        // process Data; anything to catch??  How to test??
        $this->processData();
        // render needed components ..
        // Build up template; send it back home ..
        // if error; use different template?
        return $this->renderTemplate();
    }

    // Protected FUnctions ..
    protected function geoCodeLookupLocation() {
        if (null === $this->geocoder_results) {
            // Get all the needed for Geocode
            $geocoder = new \Geocoder\Geocoder();
            $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
            $geotools = new \League\Geotools\Geotools();
            // Execute geocode on possible addresses
            $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, null, "my", true, $key_api));
            try {
                $this->geocoder_results = $geotools->batch($geocoder)->geocode($this->voter_location->getPossibleAddresses())->parallel();
                // echo "Number of results: " . count($results) . "<br/><br/>";
                // TODO: count nuebr of results back??
                // If no result; invoke ?? $voter->getBackupLocations();
            } catch (\Exception $ex) {
                $this->display['error'] = true;
                $this->display['output']['error']['message'] = "UNKNOWN EXCEPTION: " . $ex->getMessage();
            }
        }
    }

    protected function processData() {
        if (!empty($this->geocoder_results)) {
            // die(var_dump($this->geocoder_results));
            // Only do something if there are geocoder results ..
            // Data from Mapit
            $mapit = new \EC\MapIt();
            // Dump out the needed MapIt URLs ..
            foreach ($this->geocoder_results as $result) {
                if ('' == $result->getExceptionMessage()) {
                    // echo "In <br/>";
                    // die(var_dump($result));
                    // Extract out what is needed .. first hit .. probably better way ..
                    $mapit->extractMapIt($result);
                    // Extract only ..
                    $this->view_model = $mapit->getMapItViewModel();
                    // Processing done; mark the template to use
                    $this->display['output']['view'] = "result";
                } else {
                    $this->display['error'] = true;
                    $this->display['output']['error']['message'] = "UNKNOWN EXCEPTION: " . $result->getExceptionMessage();
                }
            }
        }
    }

    protected function renderTemplate() {
        // Based on the different view; should pull out different content pieces ..??
        // Initis ..
        $finalized_mapit_output = "";
        $finalized_polygon = "";
        $finalized_mapit_map = "";
        // AUto-fill input fields
        if (!empty($this->display['input'])) {
            foreach ($this->display['input'] as $key => $value) {
                switch ($key) {
                    case 'postcode':
                        $mypostcode = $value;
                        break;
                    
                    case 'error':
                        foreach ($value as $error_category => $error_message) {
                            switch ($error_category) {
                                case 'postcode':
                                    $postcode_error = '<strong style="color:#FF1919">' . $error_message . '</strong>';
                                    break;
                                default:
                                    break;
                            }
                        }
                        break;

                    default:
                        break;
                }
            }
        }
        // Default: No error; do nothing
        $finalized_error_bar = "";
        if ($this->display['error']) {
            $finalized_error_bar = $this->renderErrorContent();
        }
        // View variable should be in display['output']['view']??
        if ($this->display['output']['view'] == "result") {
            // Result view which has the map section??
            $finalized_mapit_output = $this->renderMapItContent();
            $finalized_polygon = $finalized_mapit_output['polygon'];
            $finalized_mapit_map = $finalized_mapit_output['map'];
        } else {
            // Default view?
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
  <!-- START renderPolygon -->
  $finalized_polygon
  <!-- END renderPolygon -->
</head>
<body>
  <div>
    <h2>MapIt Postcode Checker</h2>
  </div>
  <div id="body">
    $finalized_error_bar
    <div>
      <form type="get" action="/mapit">
        Postcode: <input type="text" name="postcode" value="$mypostcode" /> $postcode_error 
        <input type="submit"/>
      </form>
    </div>              
    <!-- START renderMapIt Example -->
    $finalized_mapit_map
    <!-- END renderMapIt Example -->
  </div>
</body>
</html>        
MYHTML;

        // die(var_dump($finalized_html));

        return $finalized_html;
    }

    protected function renderErrorContent() {
        $error_message = $this->display['output']['error']['message'];
        return '<div style="color:#FF1919"><strong>' . $error_message . '</strong></div>';
    }

    protected function renderMapItContent() {

        // Here's the polygon snippet
        $rendered_mapit_content['polygon'] = $this->renderPolygon();

        // Here's the map snippet
        $rendered_mapit_content['map'] = <<<HTMLOUTPUT
    <h3>MapIt Postcode Lookup Example</h3>
    <div class="row">
      <a href="javascript:;" id="togglepar">PAR</a>
      <a href="javascript:;" id="toggledun">DUN</a>
      <a href="javascript:;" id="toggledm">DM</a>
      <a href="javascript:;" id="toggleare">ARE</a>
    </div>
    <div class="row">
      <div class="span11">
        <div class="popin">
          <div id="map"></div>
        </div>
      </div>
    </div>
                
HTMLOUTPUT;

        return $rendered_mapit_content;
    }

    protected function renderPolygon() {
        // Data form the processed view model; should check if it is run wrongly with no data?? 
        $mapit_point = $this->view_model;
        // Or maybe if empty; then don't show anything??
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
        $voter_full_address = array_pop($this->voter_location->getPossibleAddresses());
        // Use dumb output first ..

        $rendered_mapit_polygon = <<<HTMLPOLYGON
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
        title: 'Voter\'s Location',
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
        
                
HTMLPOLYGON;

        return $rendered_mapit_polygon;
    }

}
