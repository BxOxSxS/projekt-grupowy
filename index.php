<?php
    require_once('vendor/autoload.php');

    use Steampixel\Route;

    $db = new mysqli("localhost", "root", "", "samochody");

    Route::add('/', function() {
        global $db;

        $q = "SELECT * FROM samochody";

        if (isset($_REQUEST['WHERE'])) {
            $a = explode(' ', $_REQUEST['WHERE']);
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
    Route::add('/', function() {
        /* example body input:
            [
                {
                    "name": "id",
                    "value": null
                },
                {
                    "name": "marka",
                    "value": "test"
                },
                {
                    "name": "model",
                    "value": "test2"
                },
                {
                    "name": "model",
                    "value": "test3"
                },
                {
                    "name": "rocznik",
                    "value": 2024
                },
                {
                    "name": "cena",
                    "value": 0
                }
            ]
        */
        $body = file_get_contents('php://input');

        $bodyArr = json_decode($body);

        if ($bodyArr == NULL) {
            http_response_code(400);
            die("Invalid JSON body:\n" . $body);
        }

        global $db;
        $columns = get_columns($db, "samochody");

        $tmp = "";
        foreach ($columns as $col) {
            foreach ($bodyArr as $element) {
                if ($col['name'] == strval($element->name)) {
                    if ($element->value == null) {
                        $tmp .= "NULL" . ", ";
                    } else if (is_int($element->value)) {
                        $tmp .= $element->value . ", ";
                    } else {
                        $pattern = '/^[a-zA-Z0-9]*$/';
                        if (preg_match($pattern, $element->value) != 1) {
                            http_response_code(400);
                            die("Value " . $element->value . " is not alphanumeratic");
                        }
                        
                        $tmp .= "\"" . $element->value . "\", ";
                    }
                    continue 2;
                }
            }
            http_response_code(400);
            die("Cound not find value for columns: " . $col['name']);
        }
        $tmp = substr($tmp, 0, -2);
        $q = "INSERT INTO samochody VALUES ($tmp)";

        try {
            $r = $db->query($q);
        } catch (Exception $e) {
            http_response_code(500);
            die("MySQL error: " . $e->getMessage());
        }

        if ($r == false) {
            http_response_code(500);
            die("MySQL error: " . $db->error);
        }

        $id = $db->insert_id;
        echo $id;
        
    }, "post");

    Route::add('/', function() {
        $body = file_get_contents('php://input');

        $bodyArr = json_decode($body);

        if ($bodyArr == NULL) {
            http_response_code(400);
            die("Invalid JSON body:\n" . $body);
        }

        global $db;
        $columns = get_columns($db, "samochody");

        $tmp = "";
        $update_tmp = "";
        $id = NULL;
        foreach ($columns as $col) {
            foreach ($bodyArr as $element) {
                if ($element->name == "id") {
                    $id = $element->value;
                };

                if ($col['name'] == strval($element->name)) {
                    if ($element->value == null) {
                        $tmp .= "NULL" . ", ";
                        $update_tmp .= "$element->name = NULL, ";
                    } else if (is_int($element->value)) {
                        $tmp .= $element->value . ", ";
                        $update_tmp .= "$element->name = $element->value, ";
                    } else {
                        $pattern = '/^[a-zA-Z0-9]*$/';
                        if (preg_match($pattern, $element->value) != 1) {
                            http_response_code(400);
                            die("Value " . $element->value . " is not alphanumeratic");
                        }
                        
                        $tmp .= "\"" . $element->value . "\", ";
                        $update_tmp .= "$element->name = \"$element->value\", ";
                    }
                    continue 2;
                }
            }
            http_response_code(400);
            die("Cound not find value for columns: " . $col['name']);
        }
        $tmp = substr($tmp, 0, -2);
        http_response_code(201);
        $q = "INSERT INTO samochody VALUES ($tmp)";
        if ($id != NULL) {
            $q2 = "SELECT * FROM samochody WHERE id = $id";
            $r2 = $db->query($q2);
            while ($_ = $r2->fetch_assoc()) {
                $update_tmp = substr($update_tmp, 0, -2);
                $q = "UPDATE samochody SET $update_tmp WHERE id = $id";
                http_response_code(204);
            }
        }

        try {
            $r = $db->query($q);
        } catch (Exception $e) {
            http_response_code(500);
            die("MySQL error: " . $e->getMessage());
        }

        if ($r == false) {
            http_response_code(500);
            die("MySQL error: " . $db->error);
        }

        if ($q[0] = "I") {
            $id = $db->insert_id;
            echo $id;
        }
    }, "put");

    Route::add('/', function() {
        $body = file_get_contents('php://input');

        $bodyArr = json_decode($body);

        if ($bodyArr == NULL) {
            http_response_code(400);
            die("Invalid JSON body:\n" . $body);
        }

        global $db;
        $columns = get_columns($db, "samochody");

        $tmp = "";
        $id = NULL;
        foreach ($columns as $col) {
            foreach ($bodyArr as $element) {
                if ($element->name == "id") {
                    $id = $element->value;
                };

                if ($col['name'] == strval($element->name)) {
                    if ($element->value == null) {
                        $tmp .= "$element->name = NULL" . ", ";
                    } else if (is_int($element->value)) {
                        $tmp .= "$element->name = $element->value, ";
                    } else {
                        $pattern = '/^[a-zA-Z0-9]*$/';
                        if (preg_match($pattern, $element->value) != 1) {
                            http_response_code(400);
                            die("Value " . $element->value . " is not alphanumeratic");
                        }
                        
                        $tmp .= "$element->name = \"$element->value\", ";
                    }
                    continue 2;
                }
            }
        }
        if ($id == NULL) {
            http_response_code(400);
            die("No ID field or is set to NULL");
        }

        $tmp = substr($tmp, 0, -2);
        $q = "UPDATE samochody SET $tmp WHERE id = $id";

        try {
            $r = $db->query($q);
        } catch (Exception $e) {
            http_response_code(500);
            die("MySQL error: " . $e->getMessage());
        }

        if ($r == false) {
            http_response_code(500);
            die("MySQL error: " . $db->error);
        }

        if($db->affected_rows == 0) {
            http_response_code(404);
            die("No row for id: $id");
        }
        http_response_code(204);
    }, "patch");

    Route::add("/", function() {
        $id = (int) $_REQUEST["id"];

        if (!is_int($id) || $id == 0) {
            http_response_code(400);
            die("Invalid or no value for id: " . $_REQUEST["id"]);
        }

        global $db;
        $pq = $db->prepare("DELETE FROM samochody WHERE id = ?");
        $pq->bind_param("i", $id);

        try {
            $r = $pq->execute();
        } catch (Exception $e) {
            http_response_code(500);
            die("MySQL error: " . $e->getMessage());
        }

        if ($r == false) {
            http_response_code(500);
            die("MySQL error: " . $db->error);
        }

        if($db->affected_rows == 0) {
            http_response_code(404);
            die("No row for id: $id");
        }
        http_response_code(204);

    }, "delete");

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