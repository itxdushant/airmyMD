<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'image'
    ];


    protected $appends = ['icon'];

    public function getIconAttribute()
    {
        return env('APP_URL') .'/uploads/'. $this->image;
    }



}
