<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'start',
        'end'
    ];



    /************************
     *    Relationships     *
     ************************/

    public function carPrices()
    {
        return $this->hasMany(CarPrice::class);
    }



    /************************
     *       Methods        *
     ************************/

    /**
     * @param \DateTime $day
     * @return mixed
     */
    public function getDaySeason(\DateTime $day)
    {
        switch ($day) {
            case ($this->isOnPeakSeason($day)):
                return $this->where('name', 'Peak')->first();

            case ($this->isOnOffSeason($day)):
                return $this->where('name', 'Off')->first();

            default:
                return $this->whereNull('start')->whereNull('end')->first();
        }
    }

    /**
     * @param \DateTime $day
     * @return bool
     */
    private function isOnPeakSeason(\DateTime $day)
    {
        $season = $this->where('name', 'Peak')->first();

        return $this->isOnSeason($day, $season);
    }

    /**
     * @param \DateTime $day
     * @return bool
     */
    private function isOnOffSeason(\DateTime $day)
    {
        $season = $this->where('name', 'Off')->first();

        return $this->isOnSeason($day, $season);
    }

    /**
     * @param \DateTime $day
     * @param Season    $season
     * @return bool
     */
    private function isOnSeason(\DateTime $day, self $season): bool
    {
        $day = Carbon::createFromDate($day);

        $start = Carbon::createFromFormat('m/d', $season->start);
        $end = Carbon::createFromFormat('m/d', $season->end);

        return $day->between($start, $end);
    }
}
