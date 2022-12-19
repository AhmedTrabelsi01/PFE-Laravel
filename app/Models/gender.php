<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gender extends Model
{
    use HasFactory;
    protected $fillable = ['name','img'];
    public $timestamps=false;

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
