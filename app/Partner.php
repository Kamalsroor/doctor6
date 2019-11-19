<?php

namespace App;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Laravel\Passport\HasApiTokens;

class Partner extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, HasApiTokens;

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

    const Waiting_Time_SELECT = [
        '00:15:00'  => '15 دقيقه',
        '00:20:00'  => '20 دقيقه',
        '00:30:00'  => '30 دقيقه',
        '01:00:00'  => '60 دقيقه',
        '01:15:00'  => '75 دقيقه',
        '01:30:00'  => '90 دقيقه',
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


    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    public function Clinic()
    {
        return $this->hasOne(Clinic::class, 'partner_id');
    }

    public function Medical()
    {
        return $this->hasOne(Medical::class, 'partner_id');
    }

    public function Nurse()
    {
        return $this->hasOne(Nurse::class, 'partner_id');
    }
}
