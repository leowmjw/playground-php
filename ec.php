<?php

// EC Routing File here ..

include 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\StackRobots\Robots;

require_once 'front.php';

return;
// Simple debugging here ...
// echo "In the ec script!!";
// $myrequest = Request::createFromGlobals();
// echo "myreq is " . $myrequest->getPathInfo() . $_SERVER['PATH_INFO'] . "<br/>";

$app = new Stack\CallableHttpKernel(function (Request $request) {
    // return new Response('Goodbye Cruel World!! <br/> The Path info is .. ' . $request->getPathInfo());
    $router = new League\Route\RouteCollection;

    // Register all the routes ...
    $router->addRoute('GET', '/view', function (Request $request, Response $response) {
        // Do something ..
        return "Route is /view <br/>" . $response->getContent();
    });

    $router->addRoute('GET', '/ec/view', function (Request $request, Response $response) {
        // Do something ..
        return "Route is /ec/view <br/>" . $response->getContent();
         
    });
    
    // Now execute the stuff
    $dispatcher = $router->getDispatcher();
    // return $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
    return $dispatcher->dispatch('GET', '/view');
    // return new Response("Hello World!!");
});

// $app = new Stack\CallableHttpKernel(function (Request $request, Response $response) {
//     return new Response('Goodbye Cruel World!! DATE:' . $response->getDate());
// });
// Need bare minimum kernel? that calls router??
// Put in a robots.txt ..

putenv('SERVER_ENV=dev');
// putenv('SERVER_ENV=production');

$app = (new Stack\Builder)
        ->push('League\\StackRobots\\Robots')
        ->resolve($app);

Stack\run($app);


