<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable=['name','email','state','phone','description'];
    public $timestamps=false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
