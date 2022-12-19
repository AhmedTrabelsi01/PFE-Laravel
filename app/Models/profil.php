<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profil extends Model
{
    protected $fillable=['name','email','profession' ,'gender_id','birth_date','age','linkedin','location','domain','img','role_id','user_id','djACA','djMD','djJU','djFab','djOUt'];
    public $timestamps=false;
    use HasFactory;
}


