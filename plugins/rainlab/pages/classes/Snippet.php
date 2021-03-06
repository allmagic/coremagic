<?php namespace RainLab\Pages\Classes;

use Lang;
use Cache;
use Config;
use ApplicationException;
use Cms\Classes\Partial;
use Cms\Classes\ComponentHelpers;
use Cms\Classes\Controller as CmsController;
use ValidationException;
use DOMDocument;

/**
 * Represents a static page snippet.
 *
 * @package rainlab\pages
 * @author Alexey Bobkov, Samuel Georges
 */
class Snippet
{
    const CACHE_PAGE_SNIPPET_MAP = 'snippet-map';

    /**
     * @var string Specifies the snippet code.
     */
    public $code;

    /**
     * @var string Specifies the snippet description.
     */
    protected $description = null;

    /**
     * @var string Specifies the snippet name.
     */
    protected $name = null;

    /**
     * @var string Snippet properties.
     */
    protected $properties;

    /**
     * @var string Snippet component class name.
     */
    protected $componentClass = null;

    /**
     * @var array Internal cache of snippet declarations defined on a page.
     */
    protected static $pageSnippetMap = [];

    /**
     * @var \Cms\Classes\ComponentBase Snippet component object.
     */
    protected $componentObj = null;

    public function __construct()
    {
    }

    /**
     * Initializes the snippet from a CMS partial.
     * @param \Cms\Classes\Partial $parital A partial to load the configuration from.
     */
    public function initFromPartial($partial)
    {
        $viewBag = $partial->getViewBag();

        $this->code = $viewBag->property('staticPageSnippetCode');
        $this->description = $partial->description;
        $this->name = $viewBag->property('staticPageSnippetName');
        $this->properties = $viewBag->property('staticPageSnippetProperties', []);
    }

    /**
     * Initializes the snippet from a CMS component information.
     * @param string $componentClass Specifies the component class.
     * @param string $componentCode Specifies the component code.
     */
    public function initFromComponentInfo($componentClass, $componentCode)
    {
        $this->code = $componentCode;
        $this->componentClass = $componentClass;
    }

    /**
     * Returns the snippet name.
     * This method should not be used in the front-end request handling.
     * @return string
     */
    public function getName()
    {
        if ($this->name !== null)
            return $this->name;

        if ($this->componentClass === null)
            return null;

        $component = $this->getComponent();
        return $this->name = ComponentHelpers::getComponentName($component);
    }

    /**
     * Returns the snippet description.
     * This method should not be used in the front-end request handling.
     * @return string
     */
    public function getDescription()
    {
        if ($this->description !== null)
            return $this->description;

        if ($this->componentClass === null)
            return null;

        $component = $this->getComponent();
        return $this->description = ComponentHelpers::getComponentDescription($component);
    }

    /**
     * Returns the snippet component class name.
     * If the snippet is a partial snippet, returns NULL.
     * @return string Returns the snippet component class name
     */
    public function getComponentClass()
    {
        return $this->componentClass;
    }

    /**
     * Returns the snippet property list as array, in format compatible with Inspector.
     */
    public function getProperties()
    {
        if (!$this->componentClass)
            return self::parseIniProperties($this->properties);
        else
            return ComponentHelpers::getComponentsPropertyConfig($this->getComponent(), false, true);
    }

    /**
     * Parses properties stored in a template in the INI format and converts them to an array.
     */
    protected static function parseIniProperties($properties, $inspectorCompatible = true)
    {
        $result = [];

        foreach ($properties as $propertyInfo => $value) {
            $qualifiers = explode('|', $propertyInfo);

            if (($cnt = count($qualifiers)) < 2) {
                // Ignore lines with invalid format
                continue;
            }

            $propertyCode = trim($qualifiers[0]);
            if (!array_key_exists($propertyCode, $result))
                $result[$propertyCode] = [
                    'property' => $propertyCode
                ];

            $paramName = trim($qualifiers[1]);

            // Handling the "[viewMode|options|list] => Display as a list" case
            if ($qualifiers[1] == 'options') {
                if ($cnt > 2) {
                    if (!array_key_exists('options', $result[$propertyCode])) 
                        $result[$propertyCode]['options'] = [];

                    $result[$propertyCode]['options'][$qualifiers[2]] = $value;
                }
            }
            else {
                $result[$propertyCode][$paramName] = $value;
            }
        }

        return array_values($result);
    }

    /**
     * Parses the static page markup and renders snippets defined on the page.
     * @param string $pageName Specifies the static page file name (the name of the corresponding content block file).
     * @param \Cms\Classes\Theme $theme Specifies a parent theme.
     * @param string $markup Specifies the markup string to process.
     * @return string Returns the processed string.
     */
    public static function processPageMarkup($pageName, $theme, $markup)
    {
        $map = self::extractSnippetsFromMarkupCached($theme, $pageName, $markup);

        $controller = CmsController::getController();

        $partialSnippetMap = SnippetManager::instance()->getPartialSnippetMap($theme);
        $controller = CmsController::getController();
        foreach ($map as $snippetDeclaration => $snippetInfo) {
            $snippetCode = $snippetInfo['code'];

            if (!isset($snippetInfo['component'])) {
                if (!array_key_exists($snippetCode, $partialSnippetMap))
                    throw new ApplicationException(sprintf('Partial for the snippet %s is not found', $snippetCode));

                $partialName = $partialSnippetMap[$snippetCode];
                $generatedMarkup = $controller->renderPartial($partialName, $snippetInfo['properties']);
            }
            else {
                $generatedMarkup = $controller->renderComponent($snippetCode);
            }

            $pattern = preg_quote($snippetDeclaration);
            $markup = mb_ereg_replace($pattern, $generatedMarkup, $markup);
        }

        return $markup;
    }

    /**
     * Returns a list of component definitions declared on the page.
     * @param string $pageName Specifies the static page file name (the name of the corresponding content block file).
     * @param \Cms\Classes\Theme $theme Specifies a parent theme.
     * @return array Returns an array of component definitions
     */
    public static function listPageComponents($pageName, $theme, $markup)
    {
        $map = self::extractSnippetsFromMarkupCached($theme, $pageName, $markup);

        $result = [];
        foreach ($map as $snippetDeclaration => $snippetInfo) {
            if (!isset($snippetInfo['component'])) continue;

            $result[] = [
                'class' => $snippetInfo['component'],
                'alias' => $snippetInfo['code'],
                'properties' => $snippetInfo['properties']
            ];
        }

        return $result;
    }

    public static function processTemplateSettingsArray($settingsArray)
    {
        if (isset($settingsArray['viewBag']['staticPageSnippetProperties']['TableData'])) {
            $rows = $settingsArray['viewBag']['staticPageSnippetProperties']['TableData'];

            $columns = ['title', 'property', 'type', 'default', 'options'];

            $properties = [];
            foreach ($rows as $row) {
                $rowHasData = false;

                foreach ($columns as $column) {
                    if (isset($row[$column]) && strlen(trim($row[$column]))) {
                        $rowHasData = true;
                        $row[$column] = trim($row[$column]);
                    }
                }

                if (!$rowHasData) continue;

                $properties['staticPageSnippetProperties['.$row['property'].'|type]'] = $row['type'];
                $properties['staticPageSnippetProperties['.$row['property'].'|title]'] = $row['title'];

                if (isset($row['default']) && strlen($row['default']))
                    $properties['staticPageSnippetProperties['.$row['property'].'|default]'] = $row['default'];

                if (isset($row['options']) && strlen($row['options'])) {
                    $options = self::dropDownOptionsToArray($row['options']);

                    foreach ($options as $index => $option) {
                        $properties['staticPageSnippetProperties['.$row['property'].'|options|'.$index.']'] = trim($option);
                    }
                }
            }

            unset($settingsArray['viewBag']['staticPageSnippetProperties']);

            foreach ($properties as $name => $value) {
                $settingsArray['viewBag'][$name] = $value;
            }
        }

        return $settingsArray;
    }

    public static function processTemplateSettings($template)
    {
        if (!isset($template->viewBag['staticPageSnippetProperties']))
            return;

        $parsedProperties = self::parseIniProperties($template->viewBag['staticPageSnippetProperties'], false);

        foreach ($parsedProperties as $index=>&$property) {
            $property['id'] = $index;

            if (isset($property['options']))
                $property['options'] = self::dropDownOptionsToString($property['options']);
        }

        $template->viewBag['staticPageSnippetProperties'] = $parsedProperties;
    }

    protected static function dropDownOptionsToArray($optionsString)
    {
        $options = explode('|', $optionsString);

        $result = [];
        foreach ($options as $index => $optionStr) {
            $parts = explode(':', $optionStr, 2);

            if (count($parts) > 1 ) {
                $key = trim($parts[0]);

                if (strlen($key)) {
                    if (!preg_match('/^[0-9a-z-_]+$/i', $key))
                        throw new ValidationException(['staticPageSnippetProperties' =>sprintf(Lang::get('rainlab.pages::lang.snippet.invalid_option_key'), $key)]);

                    $result[$key] = trim($parts[1]);
                } else
                    $result[$index] = trim($optionStr);
            } else
                $result[$index] = trim($optionStr);
        }

        return $result;
    }

    protected static function dropDownOptionsToString($optionsArray)
    {
        $result = [];
        foreach ($optionsArray as $optionIndex => $optionValue) {
            $result[] = $optionIndex.':'.$optionValue;
        }

        return implode(' | ', $result);
    }

    /**
     * Extends the partial form with Snippet fields.
     */
    public static function extendPartialForm($formWidget)
    {
        /*
         * Snippet code field
         */

        $fieldConfig = [
            'tab' => 'rainlab.pages::lang.snippet.partialtab',
            'type' => 'text',
            'label' => 'rainlab.pages::lang.snippet.code',
            'comment' => 'rainlab.pages::lang.snippet.code_comment',
            'span' => 'left'
        ];

        $formWidget->tabs['fields']['viewBag[staticPageSnippetCode]'] = $fieldConfig;

        /*
         * Snippet description field
         */

        $fieldConfig = [
            'tab' => 'rainlab.pages::lang.snippet.partialtab',
            'type' => 'text',
            'label' => 'rainlab.pages::lang.snippet.name',
            'comment' => 'rainlab.pages::lang.snippet.name_comment',
            'span' => 'right'
        ];

        $formWidget->tabs['fields']['viewBag[staticPageSnippetName]'] = $fieldConfig;

        /*
         * Snippet properties field
         */

        $fieldConfig = [
            'tab' => 'rainlab.pages::lang.snippet.partialtab',
            'type' => 'datatable',
            'height' => '150',
            'dynamicHeight' => true,
            'columns' => [
                'title' => [
                    'title' => 'rainlab.pages::lang.snippet.column_property',
                    'validation' => [
                        'required' => [
                            'message' => 'Please provide the property title',
                            'requiredWith' => 'property'
                        ]
                    ]
                ],
                'property' => [
                    'title' => 'rainlab.pages::lang.snippet.column_code',
                    'validation' => [
                        'required' => [
                            'message' => 'Please provide the property code',
                            'requiredWith' => 'title'
                        ],
                        'regex' => [
                            'pattern' => '^[a-z][a-z0-9]*$',
                            'modifiers' => 'i',
                            'message' => Lang::get('rainlab.pages::lang.snippet.property_format_error')
                        ]
                    ]
                ],
                'type' => [
                    'title' => 'rainlab.pages::lang.snippet.column_type',
                    'type' => 'dropdown',
                    'options' => [
                        'string' => 'rainlab.pages::lang.snippet.column_type_string',
                        'checkbox' => 'rainlab.pages::lang.snippet.column_type_checkbox',
                        'dropdown' => 'rainlab.pages::lang.snippet.column_type_dropdown'
                    ],
                    'validation' => [
                        'required' => [
                            'requiredWith' => 'title'
                        ]
                    ]
                ],
                'default' => [
                    'title' => 'rainlab.pages::lang.snippet.column_default',
                ],
                'options' => [
                    'title' => 'rainlab.pages::lang.snippet.column_options',
                ]
            ]
        ];

       $formWidget->tabs['fields']['viewBag[staticPageSnippetProperties]'] = $fieldConfig;
    }

    protected static function extractSnippetsFromMarkup($markup, $theme)
    {
        $map = [];
        $matches = [];
        if (preg_match_all('/\<figure\s+[^\>]+\>[^\<]*\<\/figure\>/i', $markup, $matches)) {
            foreach ($matches[0] as $snippetDeclaration) {
                $nameMatch = [];
                if (!preg_match('/data\-snippet\s*=\s*"([^"]+)"/', $snippetDeclaration, $nameMatch)) {
                    continue;
                }

                $snippetCode = $nameMatch[1];

                $properties = [];

                $propertyMatches = [];
                if (preg_match_all('/data\-property-(?<property>[^=]+)\s*=\s*\"(?<value>[^\"]+)\"/i', $snippetDeclaration, $propertyMatches)) {
                    foreach ($propertyMatches['property'] as $index => $propertyName) {
                        $properties[$propertyName] = $propertyMatches['value'][$index];
                    }
                }

                $componentMatch = [];
                $componentClass = null;
                if (preg_match('/data\-component\s*=\s*"([^"]+)"/', $snippetDeclaration, $componentMatch)) 
                    $componentClass = $componentMatch[1];

                // Apply default values for properties not defined in the markup
                // and normalize property code names.
                $properties = self::preprocessPropertyValues($theme, $snippetCode, $componentClass, $properties);

                $map[$snippetDeclaration] = [
                    'code' => $snippetCode,
                    'component' => $componentClass,
                    'properties' => $properties
                ];
            }
        }

        return $map;
    }

    protected static function extractSnippetsFromMarkupCached($theme, $pageName, $markup)
    {
        if (array_key_exists($pageName, self::$pageSnippetMap)) {
            return self::$pageSnippetMap[$pageName];
        }

        $key = crc32($theme->getPath()).self::CACHE_PAGE_SNIPPET_MAP;

        $map = null;
        $cached = Cache::get($key, false);

        if ($cached !== false && ($cached = @unserialize($cached)) !== false) {
            if (array_key_exists($pageName, $cached)) {
                $map = $cached[$pageName];
            }
        }

        if (!is_array($map)) {
            $map = self::extractSnippetsFromMarkup($markup, $theme);

            if (!is_array($cached))
                $cached = [];

            $cached[$pageName] = $map;
            Cache::put($key, serialize($cached), Config::get('cms.parsedPageCacheTTL', 10));
        }

        self::$pageSnippetMap[$pageName] = $map;

        return $map;
    }

    /**
     * Apples default property values and fixes property names.
     *
     * As snippet properties are defined with data attributes, they are lower case, whereas
     * real property names are case sensitive. This method finds original property names defined
     * in snippet classes or partials and replaces property names defined in the static page markup.
     */
    protected static function preprocessPropertyValues($theme, $snippetCode, $componentClass, $properties)
    {
        $snippet = SnippetManager::instance()->findByCodeOrComponent($theme, $snippetCode, $componentClass, true);
        if (!$snippet) {
            throw new ApplicationException(Lang::get('rainlab.pages::lang.snippet.not_found', ['code'=>$snippetCode]));
        }

        $properties = array_change_key_case($properties);
        $snippetProperties = $snippet->getProperties();
        foreach ($snippetProperties as $propertyInfo) {
            $propertyCode = $propertyInfo['property'];
            $lowercaseCode = strtolower($propertyCode);

            if (!array_key_exists($lowercaseCode, $properties)) {
                if (array_key_exists('default', $propertyInfo)) {
                    $properties[$propertyCode] = $propertyInfo['default'];
                }
            }
            else {
                $markupPropertyInfo = $properties[$lowercaseCode];
                unset($properties[$lowercaseCode]);
                $properties[$propertyCode] = $markupPropertyInfo;
            }
        }

        return $properties;
    }

    /**
     * Returns a component corresponding to the snippet.
     * This method should not be used in the front-end request handling code.
     * @return \Cms\Classes\ComponentBase
     */
    protected function getComponent()
    {
        if ($this->componentClass === null)
            return null;

        if ($this->componentObj !== null)
            return $this->componentObj;

        $componentClass = $this->componentClass;

        return $this->componentObj = new $componentClass();
    }
}