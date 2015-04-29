<?php namespace Allmagic\Storemagic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLinksTable extends Migration
{

    public function up()
    {
        Schema::create('allmagic_storemagic_links', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('app_id')->unsigned();
            $table->string('url');
            $table->string('version')->index();
            $table->integer('user_id')->index();
            $table->string('vendor')->index();
            $table->string('fileSizeBytes');
            $table->string('minimumOsVersion')->index();
            $table->string('formattedPrice');
            $table->string('releaseNotes');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('allmagic_storemagic_links', function(Blueprint $table) {
            $table->foreign('app_id')->references('id')->on('apps')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('allmagic_storemagic_links');
        Schema::table('allmagic_storemagic_links', function(Blueprint $table) {
            $table->dropForeign('links_app_id_foreign');
        });
    }

}
