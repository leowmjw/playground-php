<?php
/**
 * Created by PhpStorm.
 * User: leow
 * Date: 2/4/16
 * Time: 7:52 PM
 */

// Used only for standalone debugging?
// Production use Nginx rewrite; passing on URI and parameters unchanged ..
if (file_exists($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    // echo "DOC_ROOT " . $_SERVER['DOCUMENT_ROOT'];
    // echo 'URI is ' . $_SERVER['REQUEST_URI'] . "<br/>";
    require "public/index.php";
}