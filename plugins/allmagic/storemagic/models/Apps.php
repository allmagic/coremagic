<?php namespace Allmagic\Storemagic\Models;

use Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * apps Model
 */
class Apps extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'allmagic_storemagic_apps';

    public $timestamps = true;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function links()
    {
        return $this->hasMany('Allmagic\Storemagic\Models\Links', 'app_id', 'id');
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
