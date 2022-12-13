<?php

namespace Milkhan\FiverMetricTool\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class InitialController
{

    public function getEvents(NovaRequest $request)
    {
        $entrees = \App\Models\Event::get(['event_id', 'event_name']);
        return $entrees;
    }
    public function getEntrees($id)
    {
        $entrees = \App\Models\Entree::where('entree_eventid', $id)->get(
            ['entree_id', 'entree_title']
        );
        return $entrees;
    }
    public function getEntreeCount($id)
    {
        $entreeStarted = \App\Models\Entree::where('entree_startdate', '>=', Carbon::now())
        ->where('entree_id', $id)
        ->count();
        return $entreeStarted;
    }


    public function data(NovaRequest $request)
    {
        $events = \App\Models\Event::get(['event_id', 'event_name']);
        Log::info('$events');
        Log::info($events);
        return inertia('FiverMetricTool', [
            'events' => $events
        ]);
    }
}
