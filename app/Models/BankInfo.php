<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'account_id',
        'status',
        'payouts_enabled'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
