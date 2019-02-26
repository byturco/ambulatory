<?php

namespace Reliqui\Ambulatory\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:reliqui_invitations|unique:reliqui_users',
            'role'  => 'required|string',
        ];
    }

    public function formInvitation()
    {
        return [
            'email' => $this->email,
            'role'  => $this->role,
            'token' => Str::limit(md5($this->email.Str::random()), 25, ''),
        ];
    }
}
