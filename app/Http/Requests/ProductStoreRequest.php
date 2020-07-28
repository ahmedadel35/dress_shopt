<?php

namespace App\Http\Requests;

use App\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        app()->setLocale(\Session::get('locale') ?? 'en');
        // $res = (object) request()->all();
        // dd(request()->all());
        // $data = [];
        // for ($i = 0; $i < sizeof($res->keys); $i++) {
        //     $val = $res->vals[$i];
        //     if ($val === '0' || $val === '1') {
        //         $val = (bool) $val;
        //     }
        //     $data[$res->keys[$i]] = $val;
        // }
        // dd($data);
        return Gate::allows('root');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:5',
            'price' => 'required|numeric|min:1',
            'save' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:1',
            'colors' => 'required|array',
            'sizes' => 'required|array',
            'info' => 'required|string|min:5',
            'category_slug' => 'required|string|exists:categories,slug',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes|image|mimes:jpeg,jpg,png|max:300',
            'tags' => 'required|array',
            'tags.*' => 'required|string|exists:tags,slug',
            // 'more' => 'required|string|min:5',
            'keys' => 'required|array',
            'vals' => 'required|array' 
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('product.form.title'),
            'price' => __('product.form.price'),
            'save' => __('product.form.save'),
            'qty' => __('product.form.qty'),
            'colors' => __('product.form.colors'),
            'sizes' => __('product.form.sizes'),
            'info' => __('product.form.info'),
            'category_slug' => __('product.form.category_slug'),
            'images' => __('product.form.images'),
            'tags' => __('product.form.tags'),
            // 'more' => __('product.form.more'),
            'keys' => __('product.form.keys'),
            'vals' => __('product.form.vals'),
        ];
    }
}
