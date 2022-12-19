<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class meet extends Model
{


    protected $fillable=['project_id','StartTime','EndTime','Subject','state_meet'];
    use HasFactory;
     /**
     * Interact with the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
   
    public function projects(){
        return $this->belongsTo(project::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
