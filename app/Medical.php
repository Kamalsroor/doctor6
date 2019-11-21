<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Medical
 * @package App
 */
class Medical extends Model
{
    use SoftDeletes;

    public $table = 'medicals';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'partner_id',
        'address',
        'long',
        'lat',
        'waiting_time',
        'info',
    ];

}
