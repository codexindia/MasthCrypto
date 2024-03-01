<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'date_of_birth',
        'phone_number',
        'country_code',
        'profile_pic',
        'language'
    ];
    function toCamelCaseMethod1($inputString)
    {
        $inputString = strtolower($inputString);
        $words = explode(' ', $inputString);
        $camelCase = "";

        for ($i = 0; $i < count($words); $i++) {
            $camelCase .= ucfirst($words[$i]) . ' ';
        }

        return $camelCase;
    }
    


    protected $casts = [
        'email_verified_at' => 'datetime',

    ];
    public function Country(): HasOne
    {
        return $this->hasOne(CountryInfo::class, 'dial_code', 'country_code');
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->toCamelCaseMethod1($value),
        );
    }
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtolower($value),
        );
    }
}
