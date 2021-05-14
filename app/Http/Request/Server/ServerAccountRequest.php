<?php

namespace App\Http\Request\Server;

use Illuminate\Foundation\Http\FormRequest;

class ServerAccountRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => 'required|max:250',
            'type' => 'required|max:250',
            'secretToken' => 'required|max:250',
            'secretKey' => 'max:250',
            'description' => 'max:250',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'name.required' => 'Vui lòng nhập loại tài khoản',
            'secretToken.required' => 'Vui lòng nhập token',
            'name.max' => 'Nhập tối đa 45 ký tự',
            'secretToken.max' => 'Nhập tối đa 250 ký tự',
            'secretKey.max' => 'Nhập tối đa 250 ký tự',
            'description.max' => 'Nhập tối đa 250 ký tự',
        ];
    }

}
