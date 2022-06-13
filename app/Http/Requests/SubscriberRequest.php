<?php

namespace App\Http\Requests;

use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
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
        $isUpdate = $this->method() === 'PUT';
        $emailRule = 'unique:subscribers,email';

        if ($isUpdate) {
            $emailRule = 'unique:subscribers,email,' . $this->route('subscriber');
        }

        return [
            'email' => [
                'required',
                'email',
                $emailRule,
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, '@'), 1);

                    if (!$domain || !checkdnsrr($domain)) {
                        $fail('Invalid domain');
                    }
                },
            ],
            'name' => 'required',
            'state' => [
                'required',
                Rule::in(Subscriber::VALID_STATES),
            ],
        ];
    }
}
