<?php

namespace Departur\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => ['required'],
            'slug'        => ['required', 'max:100', 'alpha_dash', Rule::unique('schedules')],
            'calendars.*' => [Rule::exists('calendars', 'id')],
        ];
    }
}
