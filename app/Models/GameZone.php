<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameZone extends Model
{
    use HasFactory;
    protected $guarded = ['gameId'];
    protected $primaryKey = 'gameId';
}
