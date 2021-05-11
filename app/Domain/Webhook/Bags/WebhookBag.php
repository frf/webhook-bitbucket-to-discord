<?php

namespace Domain\Webhook\Bags;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserBag
 * @package Domain\User\Bags
 * @property string application
 * @property string content
 * @property string webhook
 * @property string my_webhook
 * @property integer user_id
 * @property string created_at
 */
class WebhookBag
{
    private array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public static function fromRequest($attributes)
    {
        return new self($attributes);
    }
    private static function map()
    {
        return [
            'source_id' => 'co_profissional',
            'password' => 'ds_senha',
            'name' => 'ds_nome',
            'email' => 'ds_email',
            'active' => 'st_ativo',
            'active_website' => 'st_ativo_site',
            'document_number' => 'nu_documento',
            'cpf_number' => 'co_cpf',
            'ds_ddd' => 'ds_ddd',
            'cellphone' => 'ds_telefone',
            'about' => 'ds_descricao',
            'profile_photo' => 'ds_foto',
            'schedule_default' => 'ds_modelo',
            'number_room' => 'nu_sala',
            'privete_price' => 'vl_particular',
            'privete_price_full' => 'vl_extenso',
            'description_licence' => 'ds_licenca',
            'is_licence' => 'st_licenca',
            'cnes' => 'co_cnes',
            'cbo' => 'co_cbo',
            'is_external_schedule' => 'is_externo',
            'is_children_schedule' => 'crianca_atendimento',
            'public_email_support' => 'public_email_support',
            'account_manager_id' => 'account_manager_id',
            'address_street' => "address_street",
            'address_number' => "address_number",
            'address_complement' => 'address_complement',
            'address_neighborhood' => 'address_neighborhood',
            'address_zip_code' => "address_zip_code",
            'address_city_name' => 'address_city_name',
            'address_state' => "address_state",
            'address_country' => "address_country",
        ];
    }
    public static function fromClinicanet($data)
    {
        $attributes = [];

        foreach (self::map() as $select => $client) {
            if (property_exists($data, $client) && $data->{$client} == null) {
                $data->{$client} = 0;
            }

            if (property_exists($data, $client)) {
                $attributes[$select] = $data->{$client};
            }
        }

        $attributes['name'] = self::convertUtf8($attributes, 'name');
        $attributes['active'] = self::convertFieldBool($attributes, 'active', 1);
        $attributes['active_website'] = self::convertFieldBool($attributes, 'active_website', 1);
        $attributes['is_licence'] = self::convertFieldBool($attributes, 'is_licence', 'S');
        $attributes['is_external_schedule'] = self::convertFieldBool($attributes, 'is_external_schedule','S');
        $attributes['is_children_schedule'] = self::convertFieldBool($attributes, 'is_children_schedule', 'S');
        $attributes['password'] = (isset($attributes['password'])) ?
            Hash::make($attributes['password']) :
            Hash::make('123456');
        $attributes['source'] = 'clinicanet';
        $attributes['cellphone'] = $attributes['ds_ddd'] . $attributes['cellphone'];
        unset($attributes['ds_ddd']);

        return new self($attributes);
    }
    private static function convertUtf8($attributes, $attribute){
        if(isset($attributes[$attribute])) {
//            dump('Normal',$attributes[$attribute]);
//            dump('Convertido',mb_convert_encoding($attributes[$attribute], 'UTF-8'));
            return utf8_decode($attributes[$attribute]);
        }
        return false;
    }
    private static function convertFieldBool($attributes, $attribute, $check){
        if(isset($attributes[$attribute])) {
            return ($attributes[$attribute] == $check);
        }
        return false;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }
}
