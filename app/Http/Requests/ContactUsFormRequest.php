<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        app()->setLocale(\Session::get('locale') ?? 'en');
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
            'userName' => 'nullable|string',
            'userMail' => 'required|email',
            'userMessage' => 'required|string|min:5'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'userName' => __('contact.userName'),
            'userMail' => __('auth.E-Mail-Address'),
            'userMessage' => __('contact.message')
        ];
    }
}
