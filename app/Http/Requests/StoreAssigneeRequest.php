<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssigneeRequest extends FormRequest
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
            'assignee' => ['required', 'string'],
            'assignee_code' => ['required', 'string', 'regex:/^[0-9]*$/'],
            'position' => ['required'],
            'account_no' => ['required', 'string', 'min:9', 'max:10', 'regex:/^[0-9]*$/'],
            'phone_no' => ['required', 'string', 'size:11', 'regex:/^[0-9]*$/'],
            'allowance' => ['required', 'regex:/^(([0-9]*)(\.([0-9]{0,2}+))?)$/'],
            'plan' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'assignee_code' => 'ID no.',
            'phone_no' => 'phone no.',
            'account_no' => 'account no.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'plan_id' => $this->plan,
            'position_id' => $this->position,
        ]);
    }
}
