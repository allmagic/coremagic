<?php namespace Mossadal\ExtendMarkdown\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRulesTable extends Migration
{

    public function up()
    {
        Schema::create('mossadal_extendmarkdown_rules', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('start_markdown')->nullable();
            $table->string('close_markdown')->nullable();
            $table->string('start_tag')->nullable();
            $table->string('close_tag')->nullable();
            $table->boolean('is_protected')->default(false);
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mossadal_extendmarkdown_rules');
    }

}
