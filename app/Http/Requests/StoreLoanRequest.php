<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
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
            'subscription_fee' => ['required', 'regex:/^(([0-9]*)(\.([0-9]{0,2}+))?)$/'],
            'current_subscription_count' => ['required', 'string', 'regex:/^[0-9]*$/'],
            'total_subscription_count' => ['required', 'string', 'size:2', 'regex:/^[0-9]*$/'],
            'status' => ['required']
        ];
    }
}
