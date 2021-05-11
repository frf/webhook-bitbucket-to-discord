<?php

namespace App\Api\Requests;

use Illuminate\Support\Facades\Request;

class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|min:3',
            'email' => 'sometimes|email|unique:users,email',
            'password' => 'sometimes|min:5'
        ];
    }

    public function allowedToUpdate()
    {
        return [
            'name',
            'email',
            'password'
        ];
    }
}
