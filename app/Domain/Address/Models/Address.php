<?php

namespace Domain\Address\Models;

use Domain\User\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    public const TYPE_USER_CONTACT = 'user_contact';

    protected $fillable = [
        'type',
        'address',
        'number',
        'complement',
        'neighborhood',
        'postal_code',
        'city',
        'state',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(Patient::class, 'id', 'user_id');
    }
}
