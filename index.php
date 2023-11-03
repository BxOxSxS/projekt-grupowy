<?php
    require_once('vendor/autoload.php');

    use Steampixel\Route;

    $db = new mysqli("localhost", "root", "", "samochody");

    Route::add('/', function() {
        global $db;
        $q = "SELECT * FROM samochody;";
        $preparedQ = $db->prepare($q);
        if (!$preparedQ->execute()) {
            http_response_code(500);
        } else {
            $row = $preparedQ->get_result();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($row->fetch_all());
        }
    });


    Route::run('/bsadowski/projekt-grupowy')
?>