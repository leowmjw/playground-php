<?php

include "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Description: Use Guzzle to download Mapit based on lng, lat
// Process the outcoming Geocode ...
// Find out the parents all the way to the top ...
// Use the javascript given by @sweemeng to tie all the pieces together; stub out all the calls ..
// Use ENV for test ICs ..
$env_loader = new \josegonzalez\Dotenv\Loader(__DIR__ . "/../env");
$parsed_env = $env_loader->parse();
$parsed_env->toEnv();

// Fill up the polygon; how??
// Init keys
$apiKey = getenv('GOOGLE_API_KEY');

// Relevant URLs:
// / --> Top level
// /compare/<NewIC>/<Address/Postcode> --> Map router in index.php to Compare Controller?
// /compare/<OldIC>/>Address/Postcode>
// /report/mapit {POST: name, ic, email, phone, description, incorrect_par_dun_dm_data, correct_par_dun_dm_data}
// /report/ec {POST: name, ic, email, phone, description, incorrect_par_dun_dm_data, correct_par_dun_dm_data}
//echo "This is it!";
//phpinfo();
// Parse all the avialbale params ..
$myrequest = Request::createFromGlobals();
$myresponse = new Response();

// Defines Route
// Get Route Collections ..
$router = new League\Route\RouteCollection;

$router->get('/', function(Request $myrequest, Response $myresponse) use ($myrequest, $myresponse) {
    // $myresponse->setContent("In / <br/><br/> Data: " . print_r($myrequest->query->all(), true));
    $mymessage = print_r($myrequest->query->all(), true);
    $display['message'] = $mymessage;
    $default_controller = new \EC\Controller();
    $myresponse->setContent($default_controller->render($display));
    return $myresponse;
});

// MapIt Example application; putting map based on postcode; to display a MapIt data 
$router->get('/mapit', function(Request $myrequest, Response $myresponse) use ($myrequest, $myresponse, $apiKey) {
    // Query extraction raw ..
    // $apiKey = $_ENV['GOOGLE_API_KEY'];
    $mypostcode = $myrequest->get('postcode');
    $postcodemapit_controller = new \EC\ControllerPostcodeMapIt($apiKey, $mypostcode);
    // Return the generated response to be sent back ..
    // Test view model ..
    // $myresponse->setContent($postcodemapit_controller);
    $myresponse->setContent($postcodemapit_controller->render());
    return $myresponse;
});

$router->get('/compare', function(Request $myrequest, Response $myresponse) use ($myrequest, $myresponse) {
    $apiKey = $_ENV['GOOGLE_API_KEY'];
    // echo "APIKEY is: $apiKey <br/><br/>";
    $myic = $myrequest->get('ic');
    $mypostcode = $myrequest->get('postcode');
    $myaddress = $myrequest->get('address');
    // Should instead get the below from the query; maybe use as default??
    // $mypostcode = $_ENV['TEST_USER1_POSTCODE'];
    // $myaddress = $_ENV['TEST_USER1_ADDRESS'];
    // $myic = $_ENV['TEST_USER1_IC'];
    // Init pre-reqs flag
    $prereqs_met = true;
    $display = array();
    // Validation and check standard prereqs
    // reformat with htmlentities for display and reuse inside template ..
    // Clean up proper htmlentities
    $display['input']['ic'] = htmlentities($myic);
    $display['input']['postcode'] = htmlentities($mypostcode);
    $display['input']['address'] = htmlentities($myaddress);
    // CReate the needed pre-reqs; any failure gets marked
    // Dummy tests below; should be further to be injected in ..
    $ec_site = new \EC\ECSiteDummy($myic);
    // $ec_site = new \EC\ECSite($myic);
    if (empty($ec_site->getLabels())) {
        // Mark in UI structure
        // var_dump($display);
        $display['input']['error']['ic'] = "Invalid Voter!!";
        // Mark failed
        $prereqs_met = false;
    }
    $voter_location = new \EC\VoterLocation($mypostcode, $myaddress);
    if (empty($voter_location->getPossibleAddresses())) {
        // Mark in UI structure
        $display['input']['error']['postcode'] = "Invalid postcode!!";
        $display['input']['error']['address'] = "or address!!";
        // Mark failed
        $prereqs_met = false;
    }
    // Only view it if all the pre-req passed!
    if ($prereqs_met) {
        $compare_controller = new \EC\Compare($apiKey, $ec_site, $voter_location);
        // Extract out point data and voter data etc.
        $display['output'] = $compare_controller->view();
        // return back ViewModel
        // EC Data inside voter? {{ $display['output']['voter'] }}
        // $view['voter'] = $this->voter->getDetails();
        /*
          Array
          (
          [ic] => 111111145313
          [oldic] =>
          [fullname] => MR X
          [dob] => 01 Jan 1909
          [gender] => LELAKI
          [locality] => 107 / 37 / 10 / 003 - BANDAR UTAMA DAMANSARA
          [dm] => 107 / 37 / 10 - BANDAR UTAMA BU 3 - BU 8
          [dun] => 107 / 37 - BUKIT LANJAN
          [par] => 107 - SUBANG
          [state] => SELANGOR
          )
         * 
         */
        // $view['mapit'] = $mapit->getMapItViewModel();
        // MapIt Data {{ $display['output']['mapit'] }}
        // Polygon Data ..
        // Array[lat] ==> center/point
        // Array[lng] ==> center/point
        // Array[par][name]
        // Array[par][polygon]
        // Array[dun][name]
        // Array[dun][polygon]
        // Array[dm][name]
        // Array[dm][polygon]
        // Array[are][name]
        // Array[are][polygon]
        // Setup ECSite, VoterLocation here
        // Call the Compare controller ..
        // Output the magic __toString from Controller class??
        /*
          $myresponse->setContent("In /compare <br/><br/> Data: " . print_r($myrequest->query->all(), true));
         * 
         */
        // $myresponse->setContent("In /compare <br/><br/> Data: " . print_r($myrequest->query->all(), true) . "<br/><br/>" . $compare_controller);
        $myresponse->setContent($compare_controller->render($display));
    } else {
        // Error condition
        $display['error'] = true;

        $default_controller = new \EC\Controller();
        $myresponse->setContent($default_controller->render($display));
    }
    return $myresponse;
});

// Resolve now ..
$dispatcher = $router->getDispatcher();

// Try it ..
try {
    $response = $dispatcher->dispatch($myrequest->getMethod(), $myrequest->getPathInfo());
    // Send off all the contnt ..
    $response->send();
} catch (League\Route\Http\Exception $hexec) {
    echo "ERROR:" . $hexec->getMessage() . "<br/><br/>";
    echo 'Sorry; try <a href="/">/</a> or <a href="/mapit">/mapit</a>?';
} catch (Exception $exc) {
    echo nl2br($exc->getTraceAsString()) . "<br/><br/>";
    // Some some page; based on what criteria; and strategy??
    echo "Ack; died!!";
}

