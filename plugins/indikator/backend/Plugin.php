<?php namespace Indikator\Backend;

use System\Classes\PluginBase;
use Backend\Classes\Controller as BackendController;
use Event;
use Backend;
use Backend\Models\UserPreferences;
use BackendMenu;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'indikator.backend::lang.plugin.name',
            'description' => 'indikator.backend::lang.plugin.description',
            'author'      => 'indikator.backend::lang.plugin.author',
            'homepage'    => 'https://github.com/gergo85/oc-backend-plus'
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Indikator\Backend\ReportWidgets\Status' => [
                'label'   => 'indikator.backend::lang.widgets.system.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Version' => [
                'label'   => 'indikator.backend::lang.widgets.version.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Logs' => [
                'label'   => 'indikator.backend::lang.widgets.logs.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Admins' => [
                'label'   => 'indikator.backend::lang.widgets.admins.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Logins' => [
                'label'   => 'indikator.backend::lang.widgets.logins.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Server' => [
                'label'   => 'indikator.backend::lang.widgets.server.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Php' => [
                'label'   => 'indikator.backend::lang.widgets.php.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Rss' => [
                'label'   => 'indikator.backend::lang.widgets.rss.label',
                'context' => 'dashboard'
            ],
            'Indikator\Backend\ReportWidgets\Images' => [
                'label'   => 'indikator.backend::lang.widgets.images.label',
                'context' => 'dashboard'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Indikator\Backend\Components\Image' => 'image',
            'Indikator\Backend\Components\Text'  => 'text'
        ];
    }

    public function boot()
    {
        Event::listen('backend.form.extendFields', function($form)
        {
            if ($form->model instanceof Backend\Models\BackendPreferences)
            {
                $form->addFields([
                    'focus_searchfield' => [
                        'label'   => 'indikator.backend::lang.settings.search_label',
                        'type'    => 'switch',
                        'span'    => 'left',
                        'default' => 'false'
                    ],
                    'sidebar_description' => [
                        'label'   => 'indikator.backend::lang.settings.sidebar_label',
                        'type'    => 'switch',
                        'span'    => 'right',
                        'default' => 'false'
                    ],
                    'rounded_avatar' => [
                        'label'   => 'indikator.backend::lang.settings.avatar_label',
                        'type'    => 'switch',
                        'span'    => 'left',
                        'default' => 'false',
                        'comment' => 'indikator.backend::lang.settings.avatar_comment'
                    ],
                    'more_themes' => [
                        'label'   => 'indikator.backend::lang.settings.themes_label',
                        'type'    => 'switch',
                        'span'    => 'right',
                        'default' => 'false',
                        'comment' => 'indikator.backend::lang.settings.themes_comment'
                    ],
                    'virtual_keyboard' => [
                        'label'   => 'indikator.backend::lang.settings.keyboard_label',
                        'type'    => 'switch',
                        'span'    => 'left',
                        'default' => 'false',
                        'comment' => 'indikator.backend::lang.settings.keyboard_comment'
                    ]
                ]);
            }
        });

        BackendController::extend(function($controller) {
            $preferences = UserPreferences::forUser()->get('backend::backend.preferences');

            if (isset($preferences['virtual_keyboard']) && $preferences['virtual_keyboard'])
            {
                $controller->addCss('/plugins/indikator/backend/assets/css/ml-keyboard.css');
                $controller->addJs('/plugins/indikator/backend/assets/js/ml-keyboard.js');
            }

            if (isset($preferences['rounded_avatar']) && $preferences['rounded_avatar'])
            {
                $controller->addCss('/plugins/indikator/backend/assets/css/rounded-avatar.css');
            }

            if (isset($preferences['focus_searchfield']) && $preferences['focus_searchfield'])
            {
                $controller->addJs('/plugins/indikator/backend/assets/js/setting-search.js');
            }

            if (isset($preferences['more_themes']) && $preferences['more_themes'])
            {
                $controller->addJs('/plugins/indikator/backend/assets/js/setting-theme.js');
            }
        });

        BackendMenu::registerContextSidenavPartial(
            'October.System',
            'system',
            '@/plugins/indikator/backend/partials/_system_sidebar.htm'
        );
    }
}
