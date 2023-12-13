<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Categorie extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =
    ['id',
    'vcard',
    'type',
    'name',
    ];

    public function getTypeNameAttribute()
    {
        switch ($this->type) {
            case 'D':
                return 'Debit';
            case 'C':
                return 'Credit';
        }
    }

    public function vcard() : BelongsTo
    {
        return $this->belongsTo(VCard::class, 'phone_number');
    }


    public function transactions() : HasMany
    {
       return $this->hasMany(Transaction::class,'category_id');
    }
}
