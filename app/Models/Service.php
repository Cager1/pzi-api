<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function children()
    {
        return $this->childrens()->with('children');
    }

    public function jobs() {
        return $this->hasMany(Job::class);
    }

}
