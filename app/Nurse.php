<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Nurse
 * @package App
 */
class Nurse extends Model
{
    use SoftDeletes;

    public $table = 'nurses';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'partner_id',
        'experience',
        'age',
    ];

}
