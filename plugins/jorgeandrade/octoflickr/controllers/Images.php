<?php namespace JorgeAndrade\OctoFlickr\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use JorgeAndrade\OctoFlickr\Models\Image;

/**
 * Images Back-end Controller
 */
class Images extends Controller
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

        BackendMenu::setContext('JorgeAndrade.OctoFlickr', 'octoflickr', 'images');

        $this->addCss('/plugins/jorgeandrade/octoflickr/assets/css/images.css');
        
        $this->addJs('/plugins/jorgeandrade/octoflickr/assets/js/images.js');


        if ($this->action === 'update') {
            header('Location: /backend/jorgeandrade/octoflickr/images/create'); exit;
        }
    }

    public function onGetImages()
    {
        $gallery_id = post('gallery_id');
        return [
            'images' => Image::where('gallerie_id', $gallery_id)->get()->toArray()
        ];
    }

    public function onDeleteImages()
    {
        Image::destroy(post('id'));
        return [
            'succes' => true
        ];
    }
}