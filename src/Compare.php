<?php

namespace EC;

/**
 * Description of Compare
 *
 * @author leow
 */
class Compare implements CompareInterface {
    public function __construct($postcode, $address) {
        ;
    }
    public function getJSSnippet() {
        
    }

    public function view() {
        // Points look like:
        // ====================
        // Location #2
        // ====================
        // Array[0][point] ==> center/point
        // Array[0][par_polygon]
        // Array[0][dun_polygon]
        // Array[0][dm_polygon]
        // Array[0][are_polygon]
        // ====================
        // Location #2
        // ====================
        // Array[1][point] ==> center/point
        // Array[1][par_polygon]
        // Array[1][dun_polygon]
        // Array[1][dm_polygon]
        // Array[1][are_polygon]
        // Array[2] ..
    }

//put your code here
}
