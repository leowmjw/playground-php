<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EC;

/**
 * Description of MapIt
 *
 * @author leow
 */
class MapIt {
    //put your code here
    private $lng;
    private $long;
    
    public function __construct() {
        $this->lng = 100.11;
        $this->long = 3.16666;
        // echo "I am in!!";
    }
    
    public function getDetails() {
        // echo "I am in getDetails!!";
        return array("aa" => $this->lng, "bb" => $this->long);
    }
    
}
