<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\calendar\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

use Drupal\calendar\GoogleGateway;
use Drupal\calendar\Plugin\Block\EventsBlock;

/**
 * Plugin implementation of the Google Calendar formatter.
 *
 * @FieldFormatter(
 *   id = "calendar_eventslist",
 *   label = "Events List",
 *   field_types = {
 *     "calendar_calendar"
 *   }
 * )
 */
class EventsList extends FormatterBase
{
    public static function defaultSettings()
    {
        return [
           'numdays'   => EventsBlock::DEFAULT_NUMDAYS,
           'maxevents' => EventsBlock::DEFAULT_MAXEVENTS
        ] + parent::defaultSettings();
    }

    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        return [
            'numdays'   => [
                '#type'          => 'number',
                '#title'         => 'Number of future days to request from Google',
                '#default_value' => $this->getSettings('numdays'),
                '#required'      => true,
                '#min'           => 1
            ],
            'maxevents' => [
                '#type'          => 'number',
                '#title'         => 'Maximum number of events to show',
                '#default_value' => $this->getSettings('maxevents'),
                '#required'      => true,
                '#min'           => 1
            ]
        ];
    }

    public function settingsSummary()
    {
        return [
            "Number of days: {$this->getSetting('numdays')}",
            "Max Events: {$this->getSetting('maxevents')}"
        ];
    }

    public function viewElements(FieldItemListInterface $items, $lang)
    {
        $numdays   = $this->getSetting('numdays'  );
        $maxevents = $this->getSetting('maxevents');

        $start = new \DateTime();
        $end   = new \DateTime();
        $end->add(new \DateInterval("P{$numdays}D"));

        $elements = [];
        foreach ($items as $i=>$item) {
            $events  = GoogleGateway::events($item->calendarId, $start, $end);
            $display = [];
            $count   = 0;
            foreach ($events as $e) {
                if (++$count > $maxevents) { break; }

                $display[] = $e;
            }

            $elements[$i] = [
                '#theme'      => 'calendar_events',
                '#events'     => $display,
                '#calendarId' => $item->calendarId
            ];
        }
        return $elements;
    }
}
