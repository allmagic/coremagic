<?php namespace Allmagic\Storemagic;

use System\Classes\PluginBase;

/**
 * storemagic Plugin Information File
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
            'name'        => 'storemagic',
            'description' => 'Provide store magic...',
            'author'      => 'allmagic',
            'icon'        => 'icon-leaf'
        ];
    }

}
