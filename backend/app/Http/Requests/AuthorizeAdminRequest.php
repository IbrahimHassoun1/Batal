<?php

namespace App\Http\Requests;

use App\Http\Requests\RootRequest;
use Illuminate\Foundation\Http\FormRequest;


class AuthorizeAdminRequest extends RootRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->id==1) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
