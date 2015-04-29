<?php namespace Duminhtam\Istore;

use System\Classes\PluginBase;

/**
 * istore Plugin Information File
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
            'name'        => 'istore',
            'description' => 'iOS store for OctoberCMS',
            'author'      => 'duminhtam',
            'icon'        => 'icon-leaf'
        ];
    }

}
