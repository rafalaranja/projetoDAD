<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Illuminate\Database\Eloquent\Relations\Pivot;


/*extends Pivot*/


class Transaction extends Model
{
    use HasFactory;



   
    protected $table = 'transactions';
    
    public $timestamps = false;
    

    protected $fillable =
    ['id',
    'vcard',
    'date',
    'datetime',
    'type',
    'value',
    'old_balance',
    'new_balance',
    'payment_type',
    'payment_reference',
    'description',
    'category_id',
    ];
    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function vcards() : BelongsTo
    {
        return $this->belongsTo(VCard::class, 'phone_number', 'vcard');
    }

    public function categories() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'id', 'category_id');
    }

    
}
