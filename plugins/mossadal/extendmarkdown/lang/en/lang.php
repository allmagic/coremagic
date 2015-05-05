<?php

return [
    'plugin' => [
        'name' => 'ExtendMarkdown',
        'description' => 'Provides functionality to extend the markdown interpreter and to protect certain constructions from the parser (for example Mathjax code).'
    ],
    'navigation' => [
        'label' => 'Extend Markdown',
        'description' => 'Manage the Markdown extension rules',
    ],
    'model' => [
        'id' => 'Id',
        'comment' => 'Comment',
        'start_markdown' => 'Source start tag',
        'close_markdown' => 'Source close tag',
        'start_tag' => 'Output start tag',
        'close_tag' => 'Output close tag',
        'is_protected' => 'Protect'
    ],
    'rule' => [
        'rule' => 'rule',
        'new_rule' => 'New rule',
        'create' => 'Create',
        'create_and_close' => 'Create and close',
        'save' => '<u>S</u>ave',
        'save_and_close' => 'Save and close',
        'saving' => 'Saving rule...',
        'deleting' => 'Deleting rule...',
        'delete_confirm' => 'Do you really want to delete this rule?',
        'delete_success' => 'Successfully deleted those rules.',
        'cancel' => 'Cancel',
        'create_rule' => 'Create rule',
        'update_rule' => 'Edit rule',
        'preview_rule' => 'Preview rule',
        'manage_rules' => 'Manage rules',
        'return_to_list' => 'Return to rule list',
        'explanation' => <<<EOT
<h4>Short explanation of the rules definitions</h4>

<p>The plugin allows you to extend the builtin Markdown
interpreter by adding your own custom markup.</p>

<p>The plugin can also protect certain constructions from
being parsed with the builtin interpreter. This can be used
to prevent conflicts with other types of markup, for example
when using MathJax for typesetting mathematical formulas.</p>

<p>Everything between the <em>source start tag</em> and
the <em>source close tag</em> is captured. The source
tags are then replaced with the corresponding output tags.</p>

<p> If the protect flag is set, the text between the start
and close tag is <strong>not</strong> run through the builtin
Markdown parser. If the flag is not set, the replacements are
made <em>after</em> the Markdown parser has done its job.</p>

<p>All the rules are run on every blog post. The extended
Markdown rules are also available as a Twig filter
<code class="text-info">xmd</code>.</p>

<h4>Example</h4>

<p>The default rule where the start and close source tags are
<code class="text-info">@@</code> and the output tags are
<code class="text-info">&lt;strong class=&quot;text-primary&quot;&gt;</code>
and <code class="text-info">&lt;/strong&gt;</code> creates a new shorthand
where the input <code class="text-info">@@Some highlighted text@@</code>
produces <strong class="text-info">Some highlighted text</strong> in the
final output.</p>

<h4>Limitations</h4>

<p>The extended Markdown rules are matched using regular expressions.
This means that it's not possible to nest the extended rules.</p>
EOT
    ]
];