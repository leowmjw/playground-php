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
        // Validation needed??
        $this->voters_ic = $ic;
    }

    public function getLabels() {
        // INit Guzzle client ..
        $client = new Client();
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
        // Init the structure??
        $voter_details = array();

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

        return $voter_details;
    }

}
