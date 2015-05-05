<?php namespace Mossadal\ExtendMarkdown\Models;

use Model;
use Cache;

/**
 * Rule Model
 */
class Rule extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mossadal_extendmarkdown_rules';

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

    public function afterSave()
    {
        Cache::forget('cached_rules');
    }

}