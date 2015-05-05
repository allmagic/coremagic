<?php namespace Mossadal\ExtendMarkdown\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

use Mossadal\ExtendMarkdown\Models\Rule as RuleModel;

/**
 * rule Back-end Controller
 */
class Rule extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Mossadal.ExtendMarkdown', 'definitions');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $postId) {
                if (!$rule = RuleModel::find($postId))
                    continue;

                $rule->delete();
            }
        }

        return $this->listRefresh();
    }
}