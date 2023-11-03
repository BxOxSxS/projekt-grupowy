<?php
    require_once('vendor/autoload.php');

    use Steampixel\Route;

    $db = new mysqli("localhost", "root", "", "samochody");

    Route::add('/', function() {
        global $db;

        $q = "SELECT * FROM samochody ";

        if (isset($_REQUEST['ORDER'])) {
            if ($_REQUEST['ORDER_DIRECTION'] == "DESC") {
                $direction = "DESC";
            } else {
                $direction = "ASC";
            }

            $columns = get_columns($db, "samochody");
            if (!in_array($_REQUEST['ORDER'], $columns)) {
                http_response_code(400);
                die("No columns ". $_REQUEST['ORDER'] . " in [" . implode(', ', $columns) . "]");
            }
            
            $q .= " ORDER BY " . $_REQUEST['ORDER'] . " $direction";
        }

        if (isset($_REQUEST['LIMIT'])) {
            $limit = intval($_REQUEST['LIMIT']);

            if ($limit < 1) {
                http_response_code(400);
                die("Invalid LIMIT value: " . $_REQUEST['LIMIT']);
            }

            $q .= " LIMIT " . $limit;
        }

        $preparedQ = $db->prepare($q);
        if (!$preparedQ->execute()) {
            http_response_code(500);
        } else {
            $row = $preparedQ->get_result();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($row->fetch_all());
        }
    });

    Route::add('/columns', function() {
        global $db;

        $columns = get_columns($db, "samochody");

        header('Content-Type: application/json; charset=utf-8');
            echo json_encode($columns);
    });


    Route::run('/bsadowski/projekt-grupowy');


    function get_columns(mysqli $db, String $table): array {
        $result = $db->query("SHOW COLUMNS FROM $table");
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        return $columns;
    }
?>