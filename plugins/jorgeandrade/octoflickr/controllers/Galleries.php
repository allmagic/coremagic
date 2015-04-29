<?php namespace JorgeAndrade\OctoFlickr\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Galleries Back-end Controller
 */
class Galleries extends Controller
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

        BackendMenu::setContext('JorgeAndrade.OctoFlickr', 'octoflickr', 'galleries');
        
        $this->addJs('/plugins/jorgeandrade/octoflickr/assets/js/gallery.js');
    }
}