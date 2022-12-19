<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vote extends Model
{
    protected $fillable=['StartTime','state','EndTime','upVotes','downVotes'];

    use HasFactory;
}
