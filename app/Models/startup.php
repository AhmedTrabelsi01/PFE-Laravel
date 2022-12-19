<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class startup extends Model
{
    use HasFactory;

    protected $fillable=['img','name','description','user_id'];
    public $timestamps=false;
}
