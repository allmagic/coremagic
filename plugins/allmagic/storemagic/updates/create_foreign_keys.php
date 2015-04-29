<?php namespace Allmagic\Storemagic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateForeignKeys extends Migration {

    public function up()
    {
        Schema::table('allmagic_storemagic_links', function($table) {
            $table->foreign('app_id')->references('id')->on('allmagic_storemagic_apps')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('allmagic_storemagic_links', function($table) {
            $table->dropForeign('allmagic_storemagic_links_app_id_foreign');
        });
    }
}
