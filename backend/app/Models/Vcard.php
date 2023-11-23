<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Vcard as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Vcard extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'deleted_at' => 'null',
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
