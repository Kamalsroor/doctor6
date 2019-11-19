<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Partner extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'partners';

    protected $appends = [
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const TYPE_SELECT = [
        'clinic'  => 'دكتور',
        'nurse'   => 'تمريض',
        'medical' => 'مركز تحاليل / اشعه',
    ];

    protected $fillable = [
        'name',
        'type',
        'phone',
        'username',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'specialty_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getAvatarAttribute()
    {
        $file = $this->getMedia('avatar')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }
}
