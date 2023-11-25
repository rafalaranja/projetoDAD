<?php

namespace App\Models;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Vcard extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'password',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit',
    ];

    protected $hidden = ['password', 'confirmation_code', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'phone_number' => 'null',
        'password' => 'hashed',
    ];
    public function getTotalTransitions()
    {
        return Transaction::where('vcard', $this->id)->count();
    }
    public function Transitions()
    {
        return $this->hasMany(Transaction::class);
    }
}
