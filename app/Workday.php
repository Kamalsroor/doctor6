<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Clinic
 * @package App
 */
class Workday extends Model
{
    use SoftDeletes;

    public $table = 'workdays';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'day',
        'partner_id',
        'from',
        'to',
        'count'

    ];

    /**
     * @return BelongsTo
     */
    public function Partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * @return HasMany
     */
    public function WorkTimes()
    {
        return $this->hasMany(Worktime::class, 'workday_id');
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getDay($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    /**
     * @param $value
     */
    public function setDay($value)
    {
        $this->attributes['day'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    /**
     * @param $value
     */
    public function setFrom($value)
    {
        $this->attributes['from'] = $value ? Carbon::createFromFormat(config('panel.time_format'), $value)->format('h:i') : null;
    }

    /**
     * @param $value
     */
    public function setTo($value)
    {
        $this->attributes['to'] = $value ? Carbon::createFromFormat(config('panel.time_format'), $value)->format('h:i') : null;
    }


}
