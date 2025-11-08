<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'business_permissions';

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    // Relations
    public function roles()
    {
        return $this->belongsToMany(BusinessRole::class, 'business_role_permission', 'business_permission_id', 'business_role_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
