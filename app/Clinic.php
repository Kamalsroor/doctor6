<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Clinic
 * @package App
 */
class Clinic extends Model
{
    use SoftDeletes;

    public $table = 'clinics';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'partner_id',
        'address',
        'price',
        'long',
        'lat',
        'waiting_time',
        'info',
    ];

}
