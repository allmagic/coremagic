# Technical documentation

## System requirement

The plugin uses the `markdown.parse` and `markdown.beforeParse`
event that are emitted by the \October\Rain\Support\Markdown
helper class just before and just after the Parsedown interpreter
has parsed Markdown input to HTML.

These events were added to octobercms/library in commit e9152ee on
January 18, 2015. If you are using an older build, you first need
to update.

## Rule definitions

The plugin allows the user to define a number of regular expressions
that are applied before and/or after Parsedown has interpreted Markdown
input.

Each rule comes with four properties:

* `start_markdown`
* `close_markdown`
* `start_tag`
* `close_tag`
* `is_protected`

If the `is_protected` flag is set, the plugin captures and removes the
input between  the `start_markdown` and `close_markdown` tags (including
the tags) before the text is sent through Parsedown.

After Parsedown has returned the resulting HTML, the plugin puts the
captured input back into the correct place, also replacing the `start_markdown`
and `close_markdown` tags with the corresponding `start_tag` and `close_tag`,
respectively.

If the `is_protected` flag is *not* set, the replacement of

> `start_markdown` + input + `close_markdown`

to

> `start_tag` + input + `close_tag`
is done in one go, **after** the Parsedown phase.

## Examples

The plugin comes with four predefined rules, where `is_protected` is set.
All of these are meant to protect MathJax markup from being destroyed
by Parsedown. For example, one rule has all four tags set to `$`, thus
protecting inline mathematical formulas, making sure that Parsedown
does not destroy the MathJax markup, so that expressions like

    $x_{n+1} + x_{n+2}$

are left untouched by Parsedown.

## How does the plugin work?

The plugin hooks into `markdown.beforeParse` and `markdown.Parse`.

The hook in `markdown.beforeParse` goes through all the defined rules
where `is_protected` is set, replacing text matching the tags with
a generated string containing an uuid for identification.

The hook in `markdown.Parse` then replaces the generated uuid strings
with the correct content, possibly replacing the surrounding tags.

Finally, the `markdown.Parse hook also goes through the rules where
`is_protected` is **not** set and performs the relevant replacements.
