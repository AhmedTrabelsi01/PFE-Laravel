<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    use HasFactory;
    protected $fillable=['name','owner','estimated_date','description','img','user_id','archiveState','pre_projectState','finishState','projectState'];
    public $timestamps=false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postulation()
    {
        return $this->hasMany(postulation::class);
    }
    public function meet(){
        return $this->hasMany('App\Models\meet');
    }

}
