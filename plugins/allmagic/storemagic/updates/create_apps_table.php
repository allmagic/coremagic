<?php namespace Allmagic\Storemagic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAppsTable extends Migration
{

    public function up()
    {
        Schema::create('allmagic_storemagic_apps', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('trackName');
            $table->string('description');
            $table->integer('votes');
            $table->integer('clicks')->unsigned()->default('0');
            $table->string('sellerName')->index();
            $table->string('supportedDevices')->index();
            $table->float('price')->default('0');
            $table->string('genres')->index();
            $table->string('screenshotUrls');
            $table->string('ipadScreenshotUrls');
            $table->string('artworkUrl60');
            $table->string('artistViewUrl');
            $table->string('kind')->index();
            $table->integer('averageUserRatingForCurrentVersion');
            $table->integer('userRatingCountForCurrentVersion');
            $table->string('wrapperType')->index();
            $table->string('formattedPrice');
            $table->integer('userRatingCount');
            $table->integer('averageUserRating');
            $table->string('version');
            $table->string('minimumOsVersion');
            $table->timestamps();
            $table->softDeletes();
            $table->smallInteger('trackContentRating')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('allmagic_storemagic_apps');
    }

}
