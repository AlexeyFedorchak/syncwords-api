<?php

namespace App\Http\Requests;

use App\Exceptions\BadRequest;
use App\Rules\EventEndDateRule;
use App\Rules\EventStartDateRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
     * @throws BadRequest
     */
    public function rules(): array
    {
        if ($this->method() == 'PUT')
            return $this->putRules();

        if ($this->method() == 'PATCH')
            return $this->patchRules();

        throw new BadRequest();
    }

    /**
     * get rules for PUT request
     *
     * @return array
     */
    protected function putRules(): array
    {
        return [
            'event_title' => [
                'required',
                'string',
                'max:200'
            ],
            'event_start_date' => [
                'required',
                'date',
                new EventStartDateRule($this, $this->event)
            ],
            'event_end_date' => [
                'required',
                'date',
                'after:event_start_date',
                new EventEndDateRule($this, $this->event),
            ]
        ];
    }

    /**
     * get PATCH request rules
     *
     * @return array
     */
    protected function patchRules(): array
    {
        return [
            'event_title' => [
                'string',
                'max:200'
            ],
            'event_start_date' => [
                'date',
                new EventStartDateRule($this, $this->event)
            ],
            'event_end_date' => [
                'date',
                'after:event_start_date',
                new EventEndDateRule($this, $this->event),
            ]
        ];
    }
}
