<?php namespace Duminhtam\Istore\Models;

use Model;

/**
 * apps Model
 */
class Apps extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'duminhtam_istore_apps';

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