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
$twig->addGlobal("folder", $folder);

// routing
$router = new AltoRouter();
$router->setBasePath($folder);

$router->map('GET', '/', function() use($twig, $docs) {
    echo $twig->render('home.twig', ['docs' => array_slice($docs, 0, 10), 'pageQuantity' => count($docs) / 10, 'page' => 1]); 
}); 
$router->map('GET', '@^/home/?$', function() use($twig, $docs) {
    echo $twig->render('home.twig', ['docs' => array_slice($docs, 0, 10), 'pageQuantity' => count($docs) / 10, 'page' => 1]); 
});
$router->map('GET', '/home/[i:page]/?', function($page) use($twig, $docs) {
    echo $twig->render('home.twig', ['docs' => array_slice($docs, ($page -1) * 10, 10), 'pageQuantity' => count($docs) / 10, 'page' => $page]); 
}); 
$router->map('GET', '/detail/[i:id]/?', function($id) use($twig, $docs) { 
    echo $twig->render('detail.twig', ['doc' => $docs[array_search((int)$id, array_column(($docs), 'entity_id'))]]);
}); 

$match = $router->match();


// page not found
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header("HTTP/1.0 404 Not Found"); // custom twig to add !
}