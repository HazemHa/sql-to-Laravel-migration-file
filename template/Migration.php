<?php
require_once "./traits/Singleton.php";

class Migration
{
    use Singleton;

    public function template($ClassName,$tableName,$ColumnsString,$fullTextIndex)
    {
      return  "<?php\r\n
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class Create".$ClassName."Table extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('$tableName', function (Blueprint \$table) {
                $ColumnsString
            });
           ".$this->addIndexSearch($tableName,$fullTextIndex)."
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('$tableName');
        }
    }";
    }

    private function addIndexSearch($tableName,$fullTextIndex){
        if($fullTextIndex){
            return "\DB::statement('ALTER TABLE $tableName ADD $fullTextIndex')";
        }
        return "";
    }
}
