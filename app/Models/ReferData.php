<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferData extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function Profile(): HasMany
    {
        return $this->HasMany(User::class, 'id', 'referred_to');
    }
    public function GetMining()
    {
        return $this->belongsToMany(MiningSession::class, 'user_id', 'referred_to');
    }
    public function GetMiningCoin()
    {
        return $this->GetMining()->sum('coin') == null ? 0 : $this->GetMining()->sum('coin');
    }
}
