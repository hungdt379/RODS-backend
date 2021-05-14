<?php
namespace App\Http\Request\Groups;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest {
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
            'name' => ['required', 'max:32'],
            'description' => 'max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhóm',
            'name.max' => 'Nhập tối đa 32 ký tự',
            'description.max' => 'Nhập tối đa 255 ký tự',
        ];
    }
}
