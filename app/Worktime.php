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
class Worktime extends Model
{
    use SoftDeletes;

    public $table = 'worktimes';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'time',
        'workday_id',
        'client_id',
        'status',

    ];

    /**
     * @return BelongsTo
     */
    public function Partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * @return BelongsTo
     */
    public function WorkDay()
    {
        return $this->belongsTo(Workday::class, 'workday_id');
    }

    /**
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
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
