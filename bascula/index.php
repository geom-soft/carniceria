<?php
header('Access-Control-Allow-Origin: *');
setlocale(LC_ALL,"es_MX");
date_default_timezone_set('America/Mexico_City');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './php/composer/vendor/autoload.php';

$app = new \Slim\App;

//Peso de bascula GET
$app->get('/', function (Request $request, Response $response) {


	while(true) {
		$output = exec(".\\read_com\\com_3.exe");
		if ($output != "")  break;
	}

	$json = (array(
		"error" => FALSE,
		"message" => "Consulta correcta",
		"status" => 200,
		"data" => array(
			"results" => array(
				"peso" => trim($output)
			)
		)
	));

	//Devuelve respuesta
	$response = $response->withAddedHeader('Content-Type', 'application/json');
    $response->withJSON($json);
	
    return $response;

});

//Peso de bascula JSONP
$app->get('/jsonp', function (Request $request, Response $response) {
	
	$jsonp_callback_name = isset($_GET['callback']) ? $_GET['callback'] : "JSONP_CALLBACK";


	while(true) {
		$output = exec(".\\read_com\\com_3.exe");
		if ($output != "")  break;
	}

	$json = json_encode(array(
		"error" => FALSE,
		"message" => "Consulta correcta",
		"status" => 200,
		"data" => array(
			"results" => array(
				"peso" => trim($output)
			)
		)
	));

	//Devuelve respuesta
	$response = $response->withAddedHeader('Content-Type', 'application/json');
    $response->getBody()->write($jsonp_callback_name."(".$json.")");
	
    return $response;

});


$app->run();
?>
