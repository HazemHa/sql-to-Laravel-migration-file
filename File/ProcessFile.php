<?php
require_once "./traits/Singleton.php";

class ProcessFile
{
    use Singleton;
    /*
    to handler with file 

    */

    public function createFile($tableName, $string)
    {
        $fileName = date('Y-m-d') . "_" . time() . "_create_table_" . $tableName . ".php";

        //Pointer
        $file = fopen("./databases/".$fileName, "w");
        if ($file == false) {
            echo ("Error in opening new file");
            exit();
        }
        //Write the data
        fwrite($file,  $string);
        fclose($file);
    }
}
