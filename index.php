<?php
    require_once('vendor/autoload.php');

    use Steampixel\Route;

    $db = new mysqli("localhost", "root", "", "samochody");

    Route::add('/', function() {
        echo 'test';
    });


    Route::run('/bsadowski/projekt-grupowy')
?>