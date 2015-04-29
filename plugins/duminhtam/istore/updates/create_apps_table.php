<?php namespace Duminhtam\Istore\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAppsTable extends Migration
{

    public function up()
    {
        Schema::create('duminhtam_istore_apps', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('desc');
            $table->integer('votes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('duminhtam_istore_apps');
    }

}
