<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
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
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zipcode',
        'bio',
        'specialization',
        'patients_gender',
        'telemedicine',
        'insurance',
        'other_insurance',
        'tags',
        'latitude',
        'longitude',
        'days',
        'special_days',
        'fees',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'doctor_id', 'user_id');
    }

}
