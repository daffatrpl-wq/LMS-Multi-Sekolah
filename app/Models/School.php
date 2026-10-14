<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'address', 'contact_email', 'status', 'max_users', 'max_classes'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
