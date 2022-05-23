<?php
require './vendor/autoload.php' ;

// getting custom environnement variables from .env file, and adding them to global variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$folder = $_ENV['FOLDER'];

// data request
$data = file_get_contents('https://www.maisonapart.com/article.json');
$dataDecoded = json_decode($data);
$docs = $dataDecoded->response->docs;

// twig templates calls
$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// routing
$router = new AltoRouter();
$router->setBasePath($folder);

$router->map('GET', '/', function() use($twig, $docs) {
    echo $twig->render('home.twig', ['docs' => array_slice($docs, 0, 10), 'pageQuantity' => count($docs) / 10, 'page' => 1]); 
}); 


$match = $router->match();


// page not found
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header("HTTP/1.0 404 Not Found"); // custom twig to add !
}