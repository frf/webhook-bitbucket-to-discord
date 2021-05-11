<?php

namespace Domain\Address\Models;

use Domain\User\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'phones';

    public const TYPE_HOME = 'home';
    public const TYPE_MOBILE = 'mobile';
    public const TYPE_BUSINESS = 'business';

    protected $fillable = [
        'type',
        'identifier',
        'country_code',
        'area_code',
        'number',
    ];

    public function user()
    {
        return $this->belongsTo(Patient::class, 'id', 'user_id');
    }

    public static function extractCountryCode($str)
    {
        /* @TODO: to implement */
        return '55';
    }

    public static function extractAreaCode($str = null)
    {
        if (!is_string($str)) {
            return '';
        }

        $start = (substr($str, 0, 1) === '0') ? 1 : 0;

        return Str::of($str)->replace(' ', '')->substr($start, 2)->__toString();
    }

    public static function extractNumber($str)
    {
//        $start = (substr($str, 0, 1) === '0') ? 1 : 0;
        $end = (substr($str, 0, 1) === '0') ? strlen($str) - 2 : strlen($str) - 3;

        return Str::of($str)->replace(' ', '')->substr($end * -1)->__toString();
    }
}
