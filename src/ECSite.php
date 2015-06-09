<?php

namespace EC;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of ECSite
 *
 * @author leow
 */
class ECSite implements ECSiteInterface {

    private $voters_ic = null;

    public function __construct($ic) {
        // Validation here; 
        $parsed_ic = preg_replace("#[^0-9a-z]#i", "", $ic);
        if (!empty($parsed_ic)) {
            $this->voters_ic = $parsed_ic;
        }
        // echo "Cleaned IC is: " . $this->voters_ic;
        // $this->voters_ic = $ic;
    }

    public function getLabels() {
        // INit Guzzle client ..
        $client = new Client();
        // Init the structure??
        $voter_details = array();
        // Basic checks; should actually be like a Yii pre-reqs check ..
        if (empty($this->voters_ic)) {
            return $voter_details;
        }

        try {
            // Grab all the data back ..
            $response = $client->post('http://daftarj.spr.gov.my/DAFTARJ/DaftarjBM.aspx', [
                'body' => [
                    '__EVENTTARGET' => '',
                    '__EVENTARGUMENT' => '',
                    '__VIEWSTATE' => '/wEPDwUKMTIxODMyMDczNA9kFgICAQ9kFgICCQ9kFgICAw8PFgIeB1Zpc2libGVoZGRkeo05mdHCi+bXiGn4/P027qadiIg=',
                    '__VIEWSTATEGENERATOR' => '91B60000',
                    '__EVENTVALIDATION' => '/wEWAwKu9pylCQKp+5bqDwKztY/NDuyIuBhD1ckUZ269r6KsO+5m+RXa',
                    'txtIC' => $this->voters_ic,
                    'Semak' => 'Semak'
                ]
            ]);
            $voter_details_html = $response->getBody()->getContents();
            // Use DOMCrawler to grab out only labels ..
            $crawler = new Crawler($voter_details_html);
            // by looking at <span>
            $crawler = $crawler->filter('span');

            foreach ($crawler as $domElement) {
                $label = strtolower($domElement->getAttribute('id'));
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
        } catch (\Exception $exc) {
            // TODO: Internal log here .. AOP??
            // Test using Monolog of maybe PSR-Log ..
            // Need the App class here; to be extended everywhere .. for its config etc.
            echo "EXCEPTION in ECSite:" . nl2br($exc->getTraceAsString());
        }
        // die(print_r($voter_details));
        return $voter_details;
    }

}
