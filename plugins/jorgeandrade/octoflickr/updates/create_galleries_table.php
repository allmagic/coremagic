<?php namespace JorgeAndrade\OctoFlickr\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateGalleriesTable extends Migration
{

    public function up()
    {
        Schema::create('jorgeandrade_octoflickr_galleries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre');
            $table->string('clave')->unique();
            $table->string('tags')->nullable();
            $table->boolean('is_flickr')->default(false);
            $table->boolean('titles')->default(true);
            $table->boolean('descriptions')->default(true);
            $table->boolean('pagination')->default(true);
            $table->integer('perPage')->default(10);
            $table->integer('columns')->default(4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jorgeandrade_octoflickr_galleries');
    }

}
