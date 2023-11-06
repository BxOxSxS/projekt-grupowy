<?php
    require_once('vendor/autoload.php');

    use Steampixel\Route;

    $db = new mysqli("localhost", "root", "", "samochody");

    Route::add('/', function() {
        global $db;

        $q = "SELECT * FROM samochody";

        if (isset($_REQUEST['WHERE'])) {
            $a = explode(' ', $_REQUEST['WHERE']);
            $columns = get_columns($db, "samochody");
            $comprasion_op = ["=" , "<>", "!=", "<", ">", "<=", ">=", "<=>", "LIKE"];
            $pattern = '/^(\d+|"[a-zA-Z0-9%_]*"|\'[a-zA-Z0-9%_]*\')$/';
            $logical_op = ["AND", "&&", "OR", "||", "XOR"];

            $i = 0;
            $str = " WHERE ";
            while (true) {
                if (!isset($a[$i]) || !isset($a[$i+1]) || !isset($a[$i+2])) {
                    http_response_code(400);
                    die ("Invalid number of arguments in WHERE value");
                }


                if (!is_in_columns($db, "samochody", $a[$i])) {
                    http_response_code(400);
                    die("Column ". $a[$i] . " does not exist in table");
                }
                $str .= $a[$i] . " ";

                if (!in_array($a[$i+1], $comprasion_op)) {
                    http_response_code(400);
                    die("Invalid operator ". $a[$i+1] . " aviable: [" . implode(', ', $comprasion_op) . "]");
                }
                $str .= $a[$i+1] . " ";

                if (preg_match($pattern, $a[$i+2]) != 1) {
                    http_response_code(400);
                    die('Invalid value of compresion: ' . $a[$i+2]);
                }

                $str .= $a[$i+2] . " ";

                if (isset($a[$i+3])) {
                    if (!in_array($a[$i+3], $logical_op)) {
                        http_response_code(400);
                        die("Invalid operator ". $a[$i+3] . " aviable: [" . implode(', ', $logical_op) . "]");
                    }
                    $str .= $a[$i+3] . " ";
                    $i = $i + 4;
                    continue;
                } else {
                    break;
                }
                
            }
            $q .= $str;
        }

        if (isset($_REQUEST['ORDER'])) {
            if ($_REQUEST['ORDER_DIRECTION'] == "DESC") {
                $direction = "DESC";
            } else {
                $direction = "ASC";
            }

            if (!is_in_columns($db, "samochody", $_REQUEST['ORDER'])) {
                http_response_code(400);
                die("Column ". $_REQUEST['ORDER'] . " does not exist in table");
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

            $array = $row->fetch_all();

            if (count($array) === 0) {
                http_response_code(404);
                die("Table is empty or WHERE closure gives no results");
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($array);
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
            $columns[] = array( 'name' => $row['Field'], 'type' => $row['Type']);
        }
        return $columns;
    }

    function is_in_columns(mysqli $db, string $table, string $name): bool {
        $columns = get_columns($db, $table);

        foreach ($columns as $col) {
            if ($col['name'] == $name) {
                return true;
            }
        }
        return false;
    }
?>