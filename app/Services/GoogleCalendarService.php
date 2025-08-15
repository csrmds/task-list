<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;


class GoogleCalendarService {
    
    public function deleteEvent($googleData): bool {
        logger("TaskService deleteEvent", [$googleData]);

        
    }
}