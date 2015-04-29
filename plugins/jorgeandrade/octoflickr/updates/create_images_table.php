<?php namespace JorgeAndrade\OctoFlickr\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateImagesTable extends Migration
{

    public function up()
    {
        Schema::create('jorgeandrade_octoflickr_images', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('author')->nullable();
            $table->string('url');
            $table->integer('gallerie_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jorgeandrade_octoflickr_images');
    }

}
