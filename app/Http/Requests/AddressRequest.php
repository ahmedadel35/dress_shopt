<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'userMail' => 'nullable|email',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'address' => 'required|string',
            'dep' => 'nullable|numeric',
            'city' => 'required|string',
            'country' => 'required|string',
            'gov' => 'required|string',
            'postCode' => 'required|numeric',
            'phoneNumber' => 'required|numeric|min:00000000000|max:99999999999',
            'notes' => 'nullable'
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
            'userMail' => __('order.userMail'),
            'firstName' => __('order.firstName'),
            'lastName' => __('order.lastName'),
            'address' => __('order.address'),
            'dep' => __('order.dep'),
            'city' => __('order.city'),
            'country' => __('order.country'),
            'gov' => __('order.gov'),
            'postCode' => __('order.postal'),
            'phoneNumber' => __('order.phone'),
        ];
    }
}
