<?php

namespace App\Rules;

use App\Constants\DateTimeConstants;
use App\Models\Event;
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
     * get event to check if dates values are compatible with given value
     *
     * @var Event
     */
    protected $event;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(FormRequest $request, Event $event)
    {
        $this->eventRequest = $request;
        $this->event = $event;
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
        $endDate = Carbon::parse($value);

        if ($this->eventRequest->event_end_date) {
            $startDate = Carbon::parse($this->eventRequest->event_end_date);
        } else {
            $startDate = Carbon::parse($this->event->event_end_date);
        }

        return $endDate->diffInHours($startDate) < DateTimeConstants::MAX_DIFF_BETWEEN_START_END_DATE;
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
