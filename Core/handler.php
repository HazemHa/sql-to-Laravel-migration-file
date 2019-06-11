<?php
require_once "./traits/Singleton.php";
require_once "./Utility/Inflect.php";
require_once "./template/Migration.php";
require_once "./File/ProcessFile.php";




class handler
{
    use Singleton;

    /*
    use to build syntax of tables from Filter Unit


    */


    private $indexes = ['primary', 'index', 'unique', 'FULLTEXT KEY'];


    public  function buildTable()
    {
        foreach (Main::getInstance()->tables as $table) {

            $TableName = $table->name;
            $ClassName = Inflect::tableName($TableName);
            $query = "";
            $fullColumn = "\$table->engine = '$table->engine';" . "\r\n";
            foreach ($table->columns as $column) {
                $query = $column->query;
                $baseString = $this->buildColumn($column);
                $fullColumn .= $this->addProperties($baseString, $column) . "\r\n";
            }
            $file = Migration::getInstance()->template($ClassName, $TableName, $fullColumn, $query);

                ProcessFile::getInstance()->createFile($TableName, $file);
        }
    }

    public function buildColumn($column)
    {

        if ($column->name || in_array($column->type, $this->indexes)) {
            $parameters = $column->parameters;
            if (sizeof($parameters) != 0 && !in_array($column->type, $this->indexes)) {
                $template = "\$table->" . $column->type . "(" . $column->name . "," . implode(",", $parameters) . ")";
            } else if (in_array($column->type, $this->indexes)) {

                $template = $this->addIndexes($column);
            } else {
                $template = "\$table->" . $column->type . "(" . $column->name . ")";
            }
            return $template;
        }
    }
    public function addIndexes($column)
    {
        if (sizeof($column->parameters) == 1) {
            $template = "\$table->" . $column->type . "(" . implode(",", $column->parameters) . ");";
        } else {
            $template = "\$table->" . $column->type . "([" .  "" . implode(",", $column->parameters) . "]);";
        }

        return $template;
    }
    public function addProperties($baseString, $column)
    {
        //   echo "<br>" . var_dump($column->properties);
        if (!in_array($column->type, $this->indexes)) {
            if (isset($column->properties[0])) {
                $key = $column->properties[0];
                //   echo "<br>" . var_dump($key);
                if (isset($key["DEFAULT"])) {
                    // echo "<br>" . var_dump($key["default"]);
                    $value = $key["DEFAULT"];
                    if (strpos($value, '\'') !== false) {
                        $baseString .= "->default($value)";
                    } else $baseString .= "->default('$value')";
                }


                if (isset($key) && $key == "NULL") {
                    $baseString .= "->nullable()";
                }

                if (isset($key) && $key == "AUTO_INCREMENT") {
                    $baseString .= "->autoIncrement()";
                }
            }
            $baseString .= ";";
        }

        return $baseString;
    }
}
