<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssigneeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'account_no' => ['required', 'string', 'min:9', 'max:10', 'regex:/^[0-9]*$/'],
            'phone_no' => ['required', 'string', 'size:11', 'regex:/^[0-9]*$/'],
            'allowance' => ['required', 'regex:/^(([0-9]*)(\.([0-9]{0,2}+))?)$/'],
        ];
    }

    public function attributes()
    {
        return [
            'phone_no' => 'phone no.',
            'account_no' => 'account no.',
        ];
    }
}
