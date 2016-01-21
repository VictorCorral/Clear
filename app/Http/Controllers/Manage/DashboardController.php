<?php
namespace CodeDay\Clear\Http\Controllers\Manage;

use \CodeDay\Clear\Models;

class DashboardController extends \CodeDay\Clear\Http\Controller {

    public function getIndex()
    {
        return \View::make('dashboard');
    }

    public function getFrontPlugin()
    {
        return \View::make('front-plugin');
    }

    public function getFrontPluginData()
    {
        $thisRegistration = Models\Batch\Event\Registration
                        ::select('batches_events_registrations.*')
                        ->join('batches_events', 'batches_events_registrations.batches_event_id', '=', 'batches_events.id')
                        ->where(function($where) {
                            $where->where('email', '=', \Input::get('email'))
                                ->orWhere('parent_email', '=', \Input::get('email'));
                        })
                        ->where('batches_events.batch_id', '=', Models\Batch::Loaded()->id)
                        ->first();
        $registrations = Models\Batch\Event\Registration
                        ::where('email', '=', \Input::get('email'))
                        ->orWhere('parent_email', '=', \Input::get('email'))
                        ->orderBy('created_at')
                        ->get();

        return json_encode([
            'this_event' => ModelContracts\Registration::Model($thisRegistration, ['internal']),
            'most_recent' => ModelContracts\Registration::Model($registrations[0], ['internal']),
            'all' => ModelContracts\Registration::Collection($registrations, ['internal'])
            ]);
    }

    public function getUpdates()
    {
        if (Models\User::me()->is_admin) {
            $events = Models\Batch::Managed()->events;
        } else {
            $events = Models\User::me()->current_managed_events;
        }

        $event_update = [];
        foreach ($events as $event) {
            $event_update[$event->id] = [
                'registrations' => $event->registrations->count(),
                'here' => count($event->attendees_here),
                'today' => count($event->registrations_today),
                'this_week' => count($event->registrations_this_week),
                'percent' => $event->registration_estimate ? round(($event->registrations->count()/$event->registration_estimate)*100) : 0,
                'predicted' => $event->pretty_prediction(),
                'notify' => $event->notify->count(),
                'allow_registrations' => $event->allow_registrations
            ];
        }

        return json_encode($event_update);
    }
}