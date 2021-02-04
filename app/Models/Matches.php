<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;
    protected $fillable=['team1_id','team2_id','team1_score','team2_score'];

    public function Teams(){
        return $this->hasMany(Teams::class);
    }
}
