<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table         = 'users_group';
    protected $primaryKey    = 'group_id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
//    protected $dateFormat = 'U';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','updated_at','created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'group_name',
        'enabled',
        'created_by'

    ];


    /**
     * Validation that check request.
     *
     * @var array
     */
    public static $rulesUpdate = [
        'group_name'           =>  'required'
    ];
    public static $rulesAdd = [
        'group_name'           =>  'required',
    ];

    /**
     * Relation with other models to relation data through it.
     */

    public function User()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

}
