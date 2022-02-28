<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EventStartDateRule implements Rule
{
    /**
     * form request with provided params
     *
     * @var FormRequest
     */
    protected $eventRequest;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(FormRequest $request)
    {
        $this->eventRequest = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $startDate = Carbon::parse($value);
        $endDate = Carbon::parse($this->eventRequest->event_end_date);

        if (!$this->eventRequest->event_end_date) {
            $event = $this->eventRequest
                ->user()
                ->events()
                ->find($this->eventRequest->id);

            if ($event) {
                $endDate = Carbon::parse($event->event_end_date);
            } else {
                return false;
            }
        }

        if ($endDate > $startDate && $startDate->addHours(12) >= $endDate) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The duration between the event_start_date and event_end_date cannot exceed 12 hours.';
    }
}
