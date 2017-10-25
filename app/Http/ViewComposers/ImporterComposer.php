<?php

namespace Departur\Http\ViewComposers;

use Illuminate\View\View;

class ImporterComposer
{
    /**
     * Bind array of importers to view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('importers', $this->importers());
    }

    /**
     * Build array of available importers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function importers()
    {
        return collect(app()->tagged('importers'))
            ->mapWithKeys(function ($item) {
                return [
                    $item->id() => $item->name()
                ];
            });
    }
}