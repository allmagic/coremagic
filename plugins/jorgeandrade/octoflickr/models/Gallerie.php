<?php namespace JorgeAndrade\OctoFlickr\Models;

use Model;

/**
 * Gallery Model
 */
class Gallerie extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jorgeandrade_octoflickr_galleries';

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
    public $hasMany = [
        'images' => ['JorgeAndrade\OctoFlickr\Models\Image']
    ];

}