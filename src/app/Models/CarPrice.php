<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPrice extends Model
{
    use HasFactory, Uuids;

    protected $table = 'car_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'price',
    ];

    /**
     *
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'car_id',
        'season_id',
    ];



    /************************
     *    Relationships     *
     ************************/

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
