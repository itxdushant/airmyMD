<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];
    
    public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');
    }


}
