<?php namespace JorgeAndrade\OctoFlickr\Components;

use Cms\Classes\ComponentBase;
use JorgeAndrade\OctoFlickr\Models\Gallerie as Ga;

class Gallery extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Gallery Component',
            'description' => 'Add a gallery to frontend'
        ];
    }

    public function defineProperties()
    {
        return [
            'gallery' => [
                'title'       => 'Key of the gallery',
                'description' => 'If you leave in blank, shows the first gallery.',
                'type' => 'dropdown',
                'default'     => ''
            ]
        ];
    }

    public function getGalleryOptions()
    {
        return Ga::lists('clave', 'clave');
    }

    public function onRun()
    {
        $key = $this->property('gallery');

        if (empty($key)) {
            $gallery = Ga::first();
        }else{
            $gallery = Ga::whereClave($key)->first();
        }

        $this->addCss('/plugins/jorgeandrade/octoflickr/assets/css/thumbflickr.css');
        $this->addCss('/plugins/jorgeandrade/octoflickr/assets/css/lightbox.css');
        $this->addJs('/plugins/jorgeandrade/octoflickr/assets/js/lightbox.min.js');

        $this->page['gallery'] = $gallery;
        $this->page['columns'] = $this->getColumns($gallery->columns);
        if ($gallery->pagination) {
            $this->page['images'] = $gallery->images()->paginate($gallery->perPage);
        }else{
            $this->page['images'] = $gallery->images;

        }
    }

    public function getColumns($column)
    {
        $column = (int) $column;
        return 12 / $column;
    }


}