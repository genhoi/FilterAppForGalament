<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 10.04.2016
 * Time: 1:04
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$controller = new genhoi\Controller\WebController($request);

$action = $request->query->get('action');

if ($action) {
    /** @var Response $response */
    $response = $controller->{$action}();
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Requested-With');

    $response->headers->set('Content-Type', 'application/json');
} else {
    $response = new Response(
        'Content',
        Response::HTTP_NOT_FOUND,
        array('content-type' => 'text/plain')
    );
}
$response->prepare($request);
$response->send();

