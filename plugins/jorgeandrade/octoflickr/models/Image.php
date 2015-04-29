<?php namespace JorgeAndrade\OctoFlickr\Models;

use Model;

/**
 * Images Model
 */
class Image extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jorgeandrade_octoflickr_images';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'gallerie' => ['JorgeAndrade\OctoFlickr\Models\Gallerie']
    ];

}