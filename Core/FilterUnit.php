<?php

require_once "./traits/Singleton.php";
require_once "./Model/Column.php";


class FilterType
{
    use Singleton;


    /* 
    This class for filter SQL CODE to PHP Column Objects by REGX
    */

    // select type of column


    public  function  selectType($columnString)
    {
        // create new object of column and set it properties
        if (strpos($columnString, 'FULLTEXT KEY') !== false) {
            return null;
        }
        $column = new Column;
        preg_match('/ `\w+`/', $columnString, $ColumnName);
        $colName = $this->replaceSinglelSQL("'", $ColumnName[0]);
        $column->name = $colName;

        if (preg_match('/VARCHAR/i', $columnString)) {
            preg_match('/\d+/', $columnString, $num);
            $column->type = "string";
            array_push($column->parameters, $num[0]);
        }
        if (preg_match('/\bCHAR\b/i', $columnString)) {
            preg_match('/\d+/', $columnString, $num);
            $column->type = "char";
            array_push($column->parameters, $num[0]);
        }
        if (preg_match('/INT/i', $columnString)) {
            $column->type = "integer";
        }
        if (preg_match('/DECIMAL/i', $columnString)) {
            preg_match_all('/(?<=\()\d+|\d+(?=\))/', $columnString, $parameters);
            $column->type = "decimal";
            $column->parameters  = $parameters[0];
        }
        if (preg_match('/SMALLINT/i', $column)) {
            $column->type = "smallInteger";
        }
        if (preg_match('/BOOL/i', $columnString)) {
            $column->type = "boolean";
        }
        if (preg_match('/DATETIME/i', $columnString)) {
            $column->type = "dateTime";
        }
        if (preg_match('/\bTEXT\b/i', $columnString)) {
            $column->type = "text";
        }
        if (preg_match('/NUMERIC/i', $columnString)) {
            $column->type = "double";
            preg_match_all('/(?<=\()\d+|\d+(?=\))/', $columnString, $parameters);
            $column->parameters  = $parameters[0];
        }
        $column  = $this->indexTypes($column, $columnString);
        $column = $this->getProperties($column, $columnString);
        return $column;
    }

    private  function indexTypes($column, $columnString)
    {
        if (preg_match('/PRIMARY KEY/i', $columnString)) {

            $result = $this->reFormatText($columnString);
            $column->parameters  = $result;
            $column->type = "primary";

          
        }


        if (
            !preg_match('/PRIMARY KEY/i', $columnString) &&
            !preg_match('/FULLTEXT KEY/i', $columnString) &&
            preg_match('/KEY/', $columnString)
        ) {
            $result = $this->reFormatText($columnString);
            $column->parameters  = $result;
            $column->type = "index";
        }

        if (preg_match('/FULLTEXT KEY/i', $columnString)) {
            $column->query = $this->replaceSinglelSQL("", $columnString);
            $column->query = str_replace("KEY", "", $column->query);
        }

        if (preg_match('/UNIQUE/i', $columnString)) {
            $result = $this->reFormatText($columnString);
            $column->parameters  = $result;
            $column->type = "unique";
        }
        return $column;
    }
    private function getProperties($column, $columnString)
    {
        if (!preg_match('/NOT NULL/i', $columnString)) {
            if (!preg_match('/--/', $columnString)) {
                array_push($column->properties, "NULL");
            }
        }
        if (preg_match('/AUTO_INCREMENT/i', $columnString)) {
            array_push($column->properties, "AUTO_INCREMENT");
        }
        if (preg_match('/default/i', $columnString)) {
            preg_match('/(?<=DEFAULT )(\w+|\'.*\')/i', $columnString, $value);
            array_push($column->properties, ['DEFAULT' => $value[0]]);
        }
        return $column;
    }

    private  function reFormatText($columnString)
    {
        preg_match_all('/`\w+`/', $columnString, $parameters);
        $result = $this->replaceSinglelSQL("'", $parameters[0]);
        return $result;
    }
    private  function replaceSinglelSQL($mark, $string)
    {
        return str_replace('`', $mark, $string);
    }
}
