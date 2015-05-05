<?php namespace Mossadal\ExtendMarkdown\Classes;

use Event;
use Mossadal\ExtendMarkdown\Models\Rule;

class Hooks {

    private static $dictionary = [];

    /**
     * Performs the required changes before Parsedown sees the input
     *
     * @param MarkdownData $data Contains the text to be parsed
     * @return void. The parsed text is returned in $data
     *
     * @author Frank Wikström <frank@mossadal.se>
     **/
    public static function preMarkdownHook($data) {
        $rules = Rule::remember(5, 'cached_rules')->get();

        $text = trim($data->text);
        self::$dictionary = [];

        // Apply the rules that protect the markup from the Parsedown interpreter

        foreach($rules as $rule) {
            if ($rule->is_protected) {
                // This should be a character not found in the search pattern
                $avoid = $rule->start_markdown . $rule->close_markdown;
                $delimiter = self::getDelimiter($avoid);

                $search = $delimiter . preg_quote($rule->start_markdown) . '(.*?)' . preg_quote($rule->close_markdown) . $delimiter . 's';
                $replace ="";

                $text = preg_replace_callback(
                    $search,
                    function($matches) use($rule) {
                        $key = uniqid('xmd');
                        self::$dictionary[$key] = $rule->start_tag . $matches[1] . $rule->close_tag;
                        return $key;
                    },
                    $text
                );
            }
        }
        $data->text = $text;
    }

    /**
     * Performs the required changes after Parsedown has interpreted the input
     *
     * @param   string          $original   Contains the original text, before
     *                                      preMarkdownHook and Parsedown have done their jobs.
     * @param   MarkdownData    $data       Contains the text parsed so far
     * @return  void                        The parsed text is returned in $data
     *
     * @author Frank Wikström <frank@mossadal.se>
     **/
    public static function postMarkdownHook($original, $data) {

        $text = trim($data->text);

        $rules = Rule::remember(5, 'cached_rules')->get();

        // First put back all the protected items

        foreach(self::$dictionary as $key => $value) {
            $text = str_replace($key, $value, $text);
        }

        // Then process the other replacement rules.

        foreach ($rules as $rule) {
            if (!$rule->is_protected) {
                $avoid = $rule->start_markdown . $rule->close_markdown;
                $delimiter = self::getDelimiter($avoid);

                $search = $delimiter . preg_quote($rule->start_markdown) . '(.*?)' . preg_quote($rule->close_markdown) . $delimiter . 's';
                $replace = $rule->start_tag . '$1' . $rule->close_tag;

                $text = preg_replace($search, $replace, $text);
            }
        }

        $data->text = $text;
    }

    /**
     * Create a delimiter to use with preg_replace. We
     * need to chose one that is *not* present in the regexp
     * pattern.
     * @param string $pattern The regexp pattern
     * @return string A suitable delimiter; one of: #/@%&;:+
     */
    private static function getDelimiter($pattern)
    {
        $candidates = [ '#','/','@','%','&',';',':','+', '|' ];

        foreach ($candidates as $char) {
            if (strpos($pattern, $char) === FALSE) return $char;
        }

        throw new Exception('Could not generate suitable delimiter for regexp.');
    }
}