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
        $view->with('importers', collect(app()->tagged('importers'))
            ->mapWithKeys(function ($item) {
                return [
                    $item->id() => $item->name()
                ];
            })
        );
    }
}