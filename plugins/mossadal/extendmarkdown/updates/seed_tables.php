<?php namespace Mossadal\ExtendMarkdown\Updates;

use Mossadal\ExtendMarkdown\Models\Rule;
use October\Rain\Database\Updates\Seeder;

class SeedAllTables extends Seeder
{

    public function run()
    {
        // Create default rules for handling MathJax

        Rule::create([
            'start_markdown' => '$$',
            'close_markdown' => '$$',
            'start_tag' => '$$',
            'close_tag' => '$$',
            'is_protected' => true,
            'comment' => 'Protect $$...$$ from Parsedown'
        ]);

        Rule::create([
            'start_markdown' => '$',
            'close_markdown' => '$',
            'start_tag' => '$',
            'close_tag' => '$',
            'is_protected' => true,
            'comment' => 'Protect $...$ from Parsedown'
        ]);

        Rule::create([
            'start_markdown' => '\begin{align}',
            'close_markdown' => '\end{align}',
            'start_tag' => '\begin{align}',
            'close_tag' => '\end{align}',
            'is_protected' => true,
            'comment' => 'Protect \begin{align}...\end{align} from Parsedown'
        ]);

        Rule::create([
            'start_markdown' => '\begin{equation}',
            'close_markdown' => '\end{equation}',
            'start_tag' => '\begin{equation}',
            'close_tag' => '\end{equation}',
            'is_protected' => true,
            'comment' => 'Protect \begin{equation}...\end{equation} from Parsedown'
        ]);

         // And an example to show how we can extend the Parsedown intepreter

        Rule::create([
            'start_markdown' => '@@',
            'close_markdown' => '@@',
            'start_tag' => '<strong class="text-primary">',
            'close_tag' => '</strong>',
            'is_protected' => false,
            'comment' => 'Use @@text@@ for a <strong class="text-primary"> shortcut'
        ]);
    }

}
