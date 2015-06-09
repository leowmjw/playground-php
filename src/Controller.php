<?php

namespace EC;

/**
 * Description of Controller
 *
 * @author leow
 */
class Controller {

    //put your code here
    public function render($display) {
        if (!empty($display['message'])) {
            $mymessage = $display['message'];
        }
        if (!empty($display['bob'])) {
            // spare bob ,
            $mymessage .= $display['bob'];
        }
        if (!empty($display['input'])) {
            foreach ($display['input'] as $key => $value) {
                switch ($key) {
                    case 'ic':
                        $myic = $value;
                        break;

                    case 'postcode':
                        $mypostcode = $value;
                        break;

                    case 'address':
                        $myaddress = $value;
                        break;

                    case 'error':
                        foreach ($value as $error_category => $error_message) {
                            switch ($error_category) {
                                case 'ic':
                                    $ic_error = '<strong style="color:#FF1919">' . $error_message . '</strong>';
                                    break;

                                case 'postcode':
                                    $postcode_error = '<strong style="color:#FF1919">' . $error_message . '</strong>';
                                    break;

                                case 'address':
                                    $address_error = '<strong style="color:#FF1919">' . $error_message . '</strong>';
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
        IC: <input type="text" name="ic" value="$myic" />$ic_error 
        Postcode: <input type="text" name="postcode" value="$mypostcode" />$postcode_error 
        Address: <input type="text" name="address" value="$myaddress"/>$address_error
        <input type="submit"/>
      </form>
    </div>
    <h3>Parliament (PAR) + State Assembly (DUN) + Voting District (DM) Maps</h3>
    <div class="row">
      <div class="span11">
        Check your information against Electoral Commision data, compared with 
        the TindakMalaysia volunteer Delimitation work (<a href="http://www.tindakmalaysia.org">tindakmalaysia.org</a>)
        (served to you by MapIt from <a href="http://www.sinarproject.org">Sinar Project</a>)<br/>
        Fill in your IC, Address and submit to check ..
      </div>
    </div>
    <div>$mymessage</div>
  </div>
</body>
</html>        
MYHTML;

        if ($display['error']) {
            // return "FAIL!!! Data dump as follows: " . print_r($display, true);
        }

        return $finalized_html;
    }

}
