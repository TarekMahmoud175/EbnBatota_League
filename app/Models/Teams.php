<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    use HasFactory;
    protected $fillable=['name','position'];

    public function Matches(){
        return $this->hasMany(Matches::class);
    }
}
