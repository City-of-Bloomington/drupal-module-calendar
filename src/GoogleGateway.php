<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL2, see LICENSE
 *
 * This file is part of the Google Calendar drupal module.
 *
 * The calendar module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * The calendar module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the calendar module.  If not, see <https://www.gnu.org/licenses/old-licenses/gpl-2.0/>.
 */
namespace Drupal\calendar;

use Drupal\Core\Site\Settings;

class GoogleGateway
{
    private static function getClient()
    {
        static $client = null;

        if (!$client) {
            $config = \Drupal::config('calendar.settings');
            $GOOGLE_USER_EMAIL = $config->get('google_user_email');

            $client = new \Google_Client();
            $client->setAuthConfig(\Drupal::service('site.path').'/credentials.json');
            $client->setScopes([\Google_Service_Calendar::CALENDAR_READONLY]);
            $client->setSubject($GOOGLE_USER_EMAIL);
        }
        return $client;
    }

    /**
     * @see https://developers.google.com/google-apps/calendar/v3/reference/events/list
     * @param  string   $calendarId
     * @param  DateTime $start
     * @param  DateTime $end
     * @param  boolean  $singleEvents
     * @param  int      $maxResults
     * @return Google_Service_Calendar_EventList
     */
    public static function events($calendarId, \DateTime $start=null, \DateTime $end=null, $singleEvents=true, $maxResults=null)
    {
        $FIELDS = 'description,end,endTimeUnspecified,htmlLink,id,location,'
                . 'originalStartTime,recurrence,recurringEventId,sequence,'
                . 'start,summary,attendees,organizer';

        $opts = [
            'fields'       => "items($FIELDS)",
            'singleEvents' => $singleEvents,
            'maxResults'   => $maxResults
        ];
        if ($singleEvents) { $opts['orderBy'] = 'startTime'; }

        if ($start) { $opts['timeMin'] = $start->format(\DateTime::RFC3339); }
        if ($end  ) { $opts['timeMax'] = $end  ->format(\DateTime::RFC3339); }

        $service = new \Google_Service_Calendar(self::getClient());
        $events = $service->events->listEvents($calendarId, $opts);
        return $events;
    }

    public static function limitEvents(\Google_Service_Calendar_Events $events, int $maxevents): array
    {
        $display = [];
        $count   = 0;
        foreach ($events as $e) {
            if (++$count > $maxevents) { break; }

            $display[] = $e;
        }
        return $display;
    }
}
