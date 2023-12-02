<?php

namespace App\Models;

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VCard extends Model
{
    use HasFactory;

    protected $table = 'vcards';

    protected $fillable =
    ['phone_number',
    'name',
    'email',
    'photo_url',
    'password',
    'confirm_code',
    'blocked',
    'balance',
    'max_debit',

    ];

    public function transactions() : HasMany
    {
        return $this->hasMany(Transaction::class, 'vcard', 'phone_number');
    }

    /*public function categories()
    {
        return $this->belongsToMany(Category::class, 'transactions')
        ->using(Transaction::class);
    }*/

    public function categories() : HasMany
    {
        return $this->hasMany(Category::class, 'vcard', 'phone_number');
    }
}
