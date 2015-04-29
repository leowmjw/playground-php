<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Parse all the avialbale params ..
$request = Request::createFromGlobals();
echo "Outside is " . $request->query->get('mybob') . "<br/>";

// init new Reponse?
$myresponse = new Response('BOB!!');
$mybob = new Response('MYBOB');

// Get Route Collections ..
$router = new League\Route\RouteCollection;

// Add the routes; can be extracted into its own files ..
$router->addRoute('GET', '/view', 'view_action');

$router->addRoute('GET', '/view/{myid}/{myname}', function (Request $request, Response $response, array $args) use ($request) {
    $request->attributes->add();
    if (!empty($args['myid'])) {
        $response->setContent("BODY IS " . $args['myid']);
    }
    return $response;
});

$router->addRoute('GET', '/add', function(Request $request, Response $response) use ($request, $myresponse) {
    // return the output??
    return new Response($myresponse->getContent() . "<br/>is ADD" . $request->query->get('mybob'));
});

$router->addRoute('GET', '/edit', function(Request $request, Response $response) {
    // return the output??
    return $response->getContent() . "<br/>is EDIT";
});

// Resolve now ..
$dispatcher = $router->getDispatcher();
try {
    $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
    // Send off all the contnt ..
    $response->send();
} catch (League\Route\Http\Exception $hexec) {
    echo $hexec->getMessage();
} catch (Exception $exc) {
    echo $exc->getTraceAsString() . "<br/><br/>";
    // Some some page; based on what criteria; and strategy??
    echo "Ack; died!!";
}

function view_action(Request $req, $res, $arr) {
    $myreq = Request::createFromGlobals();
    // return new Response($myresponse->getContent() . "<br/>is VIEW" . print_r($mybob,true) . print_r($request->server,true));
    //var_dump($req);
    echo "<br/>";
    echo "<br/>";
    //var_dump($res);
    echo "<br/>";
    echo "<br/>";
    //var_dump($arr);
    echo "<br/>";
    echo "<br/>";
    // var_dump($mybob);
    return new Response('Dude Hello World!!' . $myreq->query->get('mybob'));
}
