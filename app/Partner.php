<?php

namespace App;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Laravel\Passport\HasApiTokens;

/**
 * Class Partner
 * @package App
 */
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
        'api_token',

    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    /**
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        $file = $this->getMedia('avatar')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    /**
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Clinic()
    {
        return $this->hasOne(Clinic::class, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Medical()
    {
        return $this->hasOne(Medical::class, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Nurse()
    {
        return $this->hasOne(Nurse::class, 'partner_id');
    }
}
