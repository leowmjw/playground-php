<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EC;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Description of Router
 *
 * @author leow
 */
class Router implements HttpKernelInterface {
    // use Symfony\Component\HttpFoundation\Request;
    // use Symfony\Component\HttpFoundation\Response;
//put your code here
    // var $myrequest = Request::createFromGlobals();

    // echo $myrequest->getMethod();

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) {
        
    }

// echo "SERVER_ENV is " . $myrequest->get("SERVER_ENV");
}
