<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * A role has many permissions
     */
    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'permission_role', 'permission_id', 'role_id');
    }

    /**
     * A role belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
