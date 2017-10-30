<?php

namespace Departur\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarStoreRequest extends FormRequest
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
        $importerComposer = app(\Departur\Http\ViewComposers\ImporterComposer::class);
        $importers = $importerComposer->importers()->keys()->toArray();

        return [
            'name'        => ['required'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'url'         => ['required', 'url'],
            'type'        => ['required', Rule::in($importers)],
            'schedules.*' => [Rule::exists('schedules', 'id')],
        ];
    }
}
