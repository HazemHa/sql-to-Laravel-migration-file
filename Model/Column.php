<?php

// MODEL COLUMN


class Column{
    // name of column
    public $name;
    // type of column char,varchar,int ..etc
    public $type;
    // for example decimal need two parameters decimal(10,2)
    public $parameters = [];
    // NULL ,NOT NULL , AUTO INCREMENT , DEFAULT
    public $properties = [];
    // sql code
    //  if you want to write any sql code doesn't exist in laravel functionalities
    public $query;
}
