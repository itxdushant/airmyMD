<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'doctor_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_type',
        'reason',
        'fees',
        'source_id',
        'source_name',
        'transfer_group',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'payment_count',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Profile::class, 'user_id', 'doctor_id');
    }
    
    public function patientInsurance()
    {
        return $this->hasMany(PatientInsurance::class, 'id', 'source_id');
    }
    
    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id', 'id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'id');
    }
    
    public function getPaymentCountAttribute(){
        return Payment::where('booking_id', $this->id)->count();
    }
}
