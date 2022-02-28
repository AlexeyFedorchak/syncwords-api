<?php

namespace App\Http\Requests;

use App\Rules\EventEndDateRule;
use App\Rules\EventStartDateRule;
use Illuminate\Foundation\Http\FormRequest;

class PatchEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'min:1'
            ],
            'event_title' => [
                'string',
                'max:200'
            ],
            'event_start_date' => [
                'date',
                new EventStartDateRule($this)
            ],
            'event_end_date' => [
                'date',
                'after:event_start_date',
                new EventEndDateRule($this),
            ]
        ];
    }
}
