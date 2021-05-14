<?php
namespace App\Http\Request\Server;

use Illuminate\Foundation\Http\FormRequest;

class StoreServerRequest extends FormRequest {
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
            'name' => 'required|max:255',
            'ip_address' => 'required|max:45',
            'dns_name' => 'required|max:255',
            'provider' => 'required|max:255',
            'bbbUrl' => 'required|max:255',
            'bbbSecret' => 'required|max:255',
            'maxUsers' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên server',
            'ip_address.required' => 'Vui lòng nhập server IP address',
            'dns_name.required' => 'Vui lòng nhập server DNS name',
            'provider.required' => 'Vui lòng nhập nhà cung cấp server',
            'bbbUrl.required' => 'Vui lòng nhập BBB url',
            'bbbSecret.required' => 'Vui lòng nhập BBB secret',
            'maxUsers.required' => 'Vui lòng nhập số user max mà server có thể chịu được',
            'ip_address.max' => 'Nhập tối đa 45 ký tự',
            'dns_name.max' => 'Nhập tối đa 255 ký tự',
            'provider.max' => 'Nhập tối đa 255 ký tự',
        ];
    }
}
