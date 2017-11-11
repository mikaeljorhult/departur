<?php

namespace Departur\Http\Requests;

use Departur\Http\ViewComposers\ImporterComposer;
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
        // Get array of all available importers.
        $importerComposer = app(ImporterComposer::class);
        $importers = $importerComposer->importers()->keys()->toArray();

        // Build array of rules from requested importer.
        $importerRules = [];
        if (in_array($this->input('type'), $importers)) {
            $importer = app('importers-'.$this->input('type'));
            $importerRules = $importer->rules();
        }

        return [
            'name'        => ['required'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'type'        => ['required', Rule::in($importers)],
            'schedules.*' => [Rule::exists('schedules', 'id')],
        ] + $importerRules;
    }
}
