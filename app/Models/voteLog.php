<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class voteLog extends Model
{
    protected $fillable=['user_id','vote_id','voteType'];

    use HasFactory;
}
