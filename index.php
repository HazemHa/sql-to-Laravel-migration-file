<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>sqlToMigrationFile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <form action="" method="POST">
        <div class="wrapper">
            <div class="form-group">
                <label for="exampleFormControlTextarea2">SQL CODE</label>
                <textarea class="form-control rounded-0" id="exampleFormControlTextarea2" name="sqlCode" rows="10"></textarea>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
        </div>
    </form>
</body>
<style>
    .wrapper {
        margin-left: auto;
        margin-right: auto;
        margin-top: 5vh;
        text-align: center;
        font-size: 1.5rem;
    }

    textarea::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    textarea::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #4285F4;
    }
</style>

</html>

<?php
require_once "./Core/FilterUnit.php";
require_once "./Core/handler.php";
require_once "./Model/Table.php";
require_once "./traits/Singleton.php";


class Main
{
    use Singleton;
    public $tables = [];
}
if (isset($_POST['sqlCode'])) {
    $tables = explode(";", $_POST['sqlCode']);
    foreach ($tables as  $value) {
        echo "\n";
        if ($value == null || strlen($value) < 10) {
            continue;
        }

        $table = new Table;
        // GET TABLE NAME   CREATE TABLE (\w+)
        preg_match('/(?<=CREATE TABLE).*/si', $value, $cleanSQL);
        if (preg_match('/\w+/si', $cleanSQL[0], $TableName)) {

            $table->name = $TableName[0];
        }
        // GET ENGINE TYPE IF EXIST =>  (?<=ENGINE=)\w+

        if (preg_match('/(?<=ENGINE=)\w+/si', $cleanSQL[0], $Engine)) {
            $table->engine = $Engine[0];
        }
        // GET COLUMN BODY \(([^]]+)\)
        preg_match('/\(([^]]+)\)/si', $cleanSQL[0], $ColumnsBody);
        // SPLIT COLUMNS BY REGX
        $columns =  preg_split('/(?<!(`)|(\d)),/i', $ColumnsBody[0]);
        $columns[0] = substr($columns[0], 1);
        $columns[sizeof($columns) - 1] = substr($columns[sizeof($columns) - 1], 0, -1);
        // GET PROPERTIES OF COLUMNS
        foreach ($columns as  $column) {

            if (isEmpty($column)) {
                continue;
            }
            $column = FilterType::getInstance()->selectType($column);
            array_push($table->columns, $column);
        }
        // STORE COLUMN IN ARRAY 
        array_push(Main::getInstance()->tables, $table);
    }
    handler::getInstance()->buildTable();
}

function isEmpty($column)
{
    if (strlen($column) < 1) {
        return true;
    }
}


?>