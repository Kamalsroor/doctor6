<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Laravel\Passport\HasApiTokens;
use Hash;

/**
 * @method static where(string $string, $email)
 * @mixin \Eloquent
 */
class Client extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, HasApiTokens;

    public $table = 'clients';

    protected $appends = [
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    const GENDER_SELECT = [
        'female' => 'انثي',
        'male'   => 'ذكر',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'date_of_birth',
    ];

    protected $fillable = [
        'age',
        'lat',
        'info',
        'long',
        'email',
        'phone',
        'gender',
        'address',
        'password',
        'last_name',
        'first_name',
        'created_at',
        'updated_at',
        'deleted_at',
        'date_of_birth',
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
     * @param $email
     * @return mixed
     */
    public function findForPassport($email)
    {
        return $this->where('email', $email)->first();
    }

        /**
    * Validate the password of the user for the Passport password grant.
    *
    * @param  string $password
    * @return bool
    */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class, 'client_id', 'id');
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getDateOfBirthAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    /**
     * @param $value
     */
    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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
}
