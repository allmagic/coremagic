<?php namespace Allmagic\Storemagic\Models;

use Model;

/**
 * links Model
 */
class Links extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'allmagic_storemagic_links';

    public $timestamps = true;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function getAppInfo()
    {
        return $this->belongsTo('Allmagic\Storemagic\Models\Apps', 'id', 'app_id');
    }



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
