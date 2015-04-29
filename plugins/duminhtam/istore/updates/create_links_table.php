<?php namespace Duminhtam\Istore\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLinksTable extends Migration
{

    public function up()
    {
        Schema::create('duminhtam_istore_links', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('app_id');
            $table->string('url');
            $table->string('version');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('duminhtam_istore_links');
    }

}
