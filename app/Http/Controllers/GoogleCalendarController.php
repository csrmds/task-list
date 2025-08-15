<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Services\TaskService;

class GoogleCalendarController extends Controller
{

    private $service;
    private $calendarId= 'primary';
    private $taskService;

    public function __construct() {
        //service Start
        $user= Auth::user();
        $this->taskService= new TaskService;

        $client= new Google_Client();
        $client->setAuthConfig(storage_path('app/private/googleCredentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessToken($user->access_token);

        $this->service= new Google_Service_Calendar($client);

    }

    public function listEvents() {
        $params= [
            'timeMin'=> now()->toRfc3339String(),
            'timeMax'=> now()->addDays(30)->toRfc3339String(),
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $events= $this->service->events->listEvents($this->calendarId, $params);

        return response(json_encode($events));
    }

    public function createEvent(Request $request) {
        logger('Google Calendar Controller createEvent', [$request->input('eventData')]);

        try {
            $eventData= $request->input('eventData');
        
            $event= new \Google_Service_Calendar_Event([
                'summary'=> $eventData['summary'],
                'description'=> $eventData['description'],
                'start'=> $eventData['start'],
                'end'=> $eventData['end'],
            ]);

            $event= $this->service->events->insert($this->calendarId, $event);
            
            $updateTable = $this->taskService->insertGoogleCalendarId([
                'id' => $eventData['taskId'],
                'google_calendar_id' => $event['id'],
                'google_calendar_link' => $event['htmlLink'],
            ]);

            logger('createEvent insert response: ', [$event]);

            return response()->json([
                'success'=> true,
                'message'=> 'Evento criado com sucesso',
                'data'=> [$event, $updateTable]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao criar evento',
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function updateEvent(Request $request) {
        logger('controller calendar updateEvent', [$request->input('eventData')]);

        try {
            $eventData= $request->input('eventData');

            $start= new EventDateTime();
            $start->setDateTime($eventData['start']['dateTime']);
            $start->setTimeZone($eventData['start']['timeZone']);
            logger('dataStart: ', [$start]);
            $end= new EventDateTime();
            $end->setDateTime($eventData['end']['dateTime']);
            $end->setTimeZone($eventData['end']['timeZone']);
            logger('dataEnd: ', [$end]);

            $event= $this->service->events->get($this->calendarId, $eventData['id']);
            $event->setSummary($eventData['summary']);
            $event->setDescription($eventData['description']);
            $event->setStart($start);
            $event->setEnd($end);

            $updateEvent= $this->service->events->update($this->calendarId, $event->getId(), $event);

            return response()->json([
                'success'=> true,
                'message'=> 'Evento atualizado com sucesso',
                'data'=> $updateEvent
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao tentar atualizar o evento',
                'data'=> null,
                'error'=> $e->getMessage()
            ]);
        }
        
    }

    public function deleteEvent(Request $request) {
        logger("calendar controller deleteEvent: ", [$request->input('eventData')]);
        
        try {
            $eventData= $request->input('eventData');

            //dd($eventData[]);
            
            $response= $this->service->events->delete($this->calendarId, $eventData['google_calendar_id']);
            $removeGoogleId= $this->taskService->removeGoogleCalendarId($eventData['google_calendar_id']);

            return response()->json([
                'success'=> true,
                'message'=> 'Evento deletado com sucesso',
                'data'=> [$response, $removeGoogleId]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao tentar deletar o evento',
                'error'=> $e->getMessage()
            ]);
        }

    }

}
