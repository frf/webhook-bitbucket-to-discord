<?php

namespace Domain\User\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, HasRoles, SoftDeletes;

    protected $fillable = [
        'source_id',
        'active',
        'active_website',
        'name',
        'email',
        'email_verified_at',
        'password',
        'document_number',
        'cpf_number',
        'mobile_phone',
        'source',
        'cellphone',
        'cbo',
        'cnes',
        'number_room',
        'privete_price',
        'privete_price_full',
        'about',
        'schedule_default',
        'description_licence',
        'is_licence',
        'is_external_schedule',
        'is_children_schedule',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'active_campaign_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $visible = [
        'name',
        'email',
        'mobile_phone',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
