<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class senior extends Model
{
    use HasFactory;
    protected $fillable=['name','img','email','projectName','user_id','project_id'];
    public $timestamps=false;
}
