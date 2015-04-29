<?php namespace Duminhtam\Istore\Models;

use Model;

/**
 * links Model
 */
class Links extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'duminhtam_istore_links';

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
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}