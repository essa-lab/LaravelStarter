<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
//    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table         = 'permissions';
    protected $primaryKey    = 'id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'role',
        'category'

    ];
    

    /**
     * Validation that check request.
     *
     * @var array
     */
    public static $rules = [
        'role'           =>  'required'
    ];
    public static $rulesAdd = [
        'role'              =>  'required',
        'category'          =>  'required'
    ];

    /**
     * Relation with other models to relation data through it.
     */


}
