<?php

include "../vendor/autoload.php";

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

// Use ENV for test ICs ..
$env_loader = new \josegonzalez\Dotenv\Loader(__DIR__ . "/../env");
$parsed_env = $env_loader->parse();
$parsed_env->toEnv();

// INit Guzzle client ..
$client = new Client();

// $client->get('http://daftarj.spr.gov.my/DAFTARJ/', ['cookies' => true]);
// TODO: Use ENV to supply the test datas?? and keys/state generators?
$response = $client->post('http://daftarj.spr.gov.my/DAFTARJ/DaftarjBM.aspx', [
    'body' => [
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        '__VIEWSTATE' => '/wEPDwUKMTIxODMyMDczNA9kFgICAQ9kFgICCQ9kFgICAw8PFgIeB1Zpc2libGVoZGRkeo05mdHCi+bXiGn4/P027qadiIg=',
        '__VIEWSTATEGENERATOR' => '91B60000',
        '__EVENTVALIDATION' => '/wEWAwKu9pylCQKp+5bqDwKztY/NDuyIuBhD1ckUZ269r6KsO+5m+RXa',
        'txtIC' => $_ENV['TEST_USER1_IC'],
        'Semak' => 'Semak'
    ]
        ]);

# Example 2: $_ENV['TEST_USER1_IC']
# Example 3: $_ENV['TEST_USER_OLDIC']
# var_dump($response->getBody());
// echo "Content: <br/>" . $response->getBody();
// Question: Can the DOMCrawler handle a stream instead??
// TODO: Middleware to cache; should have!!
$mybob = print_r(processDaftar($response->getBody()->getContents()), true);
echo nl2br($mybob);

function processDaftar($myhtml) {
    // Parse it out ...
    $crawler = new Crawler($myhtml);

    $crawler = $crawler->filter('span');

    // Try getting all spans first ...??
    // Init the structure??
    $voter_details = array();

    foreach ($crawler as $domElement) {
        $label = strtolower($domElement->getAttribute('id'));
        /*
          echo "ID is " . $domElement->getAttribute('id') . " with content <strong>"
          . $domElement->textContent . "</strong><br/>";
         * 
         */
        // To do; fit into type of structure??
        // Split up the PAR, DUN, DM, LOCALITY ..
        switch ($label) {
            case 'labelic':
                $voter_details['ic'] = $domElement->textContent;
                break;

            case 'labeliclama':
                $voter_details['oldic'] = $domElement->textContent;
                break;

            case 'labelnama':
                $voter_details['fullname'] = $domElement->textContent;
                break;

            case 'labeltlahir':
                $voter_details['dob'] = $domElement->textContent;
                break;

            case 'labeljantina':
                $voter_details['gender'] = $domElement->textContent;
                break;

            case 'labellokaliti':
                $voter_details['locality'] = $domElement->textContent;
                break;

            case 'labeldm':
                $voter_details['dm'] = $domElement->textContent;
                break;

            case 'labeldun':
                $voter_details['dun'] = $domElement->textContent;
                break;

            case 'labelpar':
                $voter_details['par'] = $domElement->textContent;
                break;

            case 'labelnegeri':
                $voter_details['state'] = $domElement->textContent;
                break;

            default:
                // DO nothing ..
                break;
        }
    }

    return $voter_details;
}
