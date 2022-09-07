<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'card_token',
        'card_name',
        'card_number',
        'card_date',
        'card_primary',
    ];


}
