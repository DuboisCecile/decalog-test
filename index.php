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

var_dump($twig);
