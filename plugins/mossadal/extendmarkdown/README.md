# ExtendMarkdown Plugin

A simple extension of the Rainlab.Blog plugin for October CMS allowing for
user defined extensions to Markdown input, as well as a way to protect
certain constructions from October's built-in Markdown interpreter, thus
allowing for example MathJax and Markdown to play nice with each other.

## Background

The excellent Rainlab.Blog plugin uses Markdown formatting of posts.
Unfortunately the built-in Markdown interpreter [Parsedown](http://parsedown.org)
interferes with the standard [MathJax](www.mathjax.org) library for
typesetting matematical formulas using LaTeX syntax. While Parsedown
seems to handle some MathJax constructions properly, the parser
often produces output that causes MathJax rendering to fail.

For example: in a blog post containing text like

```markdown
    The equation $x_{n+2} - 5x_{n+1} + 6x_{n} = 0$.
```

Parsedown sees the `_` as italic markers, and produces the following
HTML code

```html
    <p>The equation $x<em>{n+2} - 5x</em>{n+1} + 6x_{n} = 0$.</p>
```

The incorrectly inserted `<em>` tags destroys the LaTeX markup
and thus MathJax fails to typeset the formulas properly.

## Solution

The ExtendMarkdown plugin provides a simple, reasonable clean
solution allowing the user to create a number of replacement
"rules", protecting certain input from the Parsedown interpreter.
These rules will be applied everytime a blog post is saved.

Also, the plugin can be used to add extra shortcuts for commonly
used markup.

By default, the plugin comes with the following rules

### Rules protecting input from the Parsedown interpreter:

|Input start tag |Input close tag |Output start tag |Output close tag |
|----------------|----------------|-----------------|-----------------|
|$               |$               |$                |$                |
|$$              |$$              |$$               |$$               |
|\begin{equation}|\end{equation}  |\begin{equation} |\end{equation}   |
|\begin{align}   |\end{align}     |\begin{align}    |\end{align}      |

### Additional markup shortcuts

|Input start tag |Input close tag |Output start tag |Output close tag |
|----------------|----------------|-----------------|-----------------|
|@@              |@@              |`<strong class="text-primary">`   |`</strong>`  |

The plugin provides a backend interface for modifying and adding
additional rules as needed.

## Live MathJax updates when previewing a blog post in the backend

If you write a lot of MathJax heavy blog posts, you probably want to render
the MathJax markup, also in the backend preview.

To do this, install the [Mossadal.MathJax](https://github.com/mossadal/mathjax-plugin) plugin.