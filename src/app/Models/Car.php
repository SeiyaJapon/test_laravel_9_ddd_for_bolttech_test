<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'status',
    ];

    /**
     *
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'brand_id',
        'created_at',
        'updated_at',
    ];



    /************************
     *    Relationships     *
     ************************/

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function prices()
    {
        return $this->hasMany(CarPrice::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }



    /************************
     *       Method         *
     ************************/

    public function aux()
    {
        return 'kjlkkjl';
    }
}
