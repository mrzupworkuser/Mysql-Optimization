<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $creating = $this->getMethod() == 'POST';
        return [
            'name' => 'required|string',
            'email' => when($creating, 'required|email|unique:users,email', 'required|email'),
            'password' => when($creating, 'required|string', 'nullable|string'),
            'address' => 'nullable|array',
            'address.*.city' => 'required|string',
            'address.*.state' => 'required|string',
            'address.*.zip' => 'required|string',
        ];
    }
}
