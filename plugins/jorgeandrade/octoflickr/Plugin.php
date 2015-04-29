<?php namespace JorgeAndrade\OctoFlickr;

use System\Classes\PluginBase;

/**
 * OctoFlickr Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'OctoFlickr',
            'description' => 'Flickr Gallery for October CMS',
            'author'      => 'JorgeAndrade',
            'icon'        => 'icon-flickr'
        ];
    }

    public function registerComponents()
    {
        return [
            'JorgeAndrade\OctoFlickr\Components\Gallery' => 'gallery'
        ];
    }

    public function registerPermissions()
    {
        return [
            'jorgeandrade.octoflickr.gallery'       => ['tab' => 'OctoFlickr', 'label' => 'Access gallery'],
        ];
    }

    public function registerNavigation()
    {
        return [
            'octoflickr' => [
                'label'       => 'OctoFlickr',
                'url'         => \Backend::url('jorgeandrade/octoflickr/galleries'),
                'icon'        => 'icon-flickr',
                'permissions' => ['jorgeandrade.octoflickr.*'],
                'order'       => 600,

                'sideMenu' => [
                    'galleries' => [
                        'label'       => 'Galleries',
                        'icon'        => 'icon-flickr',
                        'url'         => \Backend::url('jorgeandrade/octoflickr/galleries'),
                        'permissions' => ['jorgeandrade.octoflickr.gallery'],
                    ],
                    'images' => [
                        'label'       => 'Add Images',
                        'icon'        => 'icon-picture-o',
                        'url'         => \Backend::url('jorgeandrade/octoflickr/images'),
                        'permissions' => ['jorgeandrade.octoflickr.gallery'],
                    ]
                ]

            ]
        ];
    }

}
