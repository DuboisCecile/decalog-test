<?php
require './vendor/autoload.php' ;

// getting custom environnement variables from .env file, and adding them to global variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$folder = $_ENV['FOLDER'];

echo $folder;
