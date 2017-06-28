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
namespace Drupal\calendar\Plugin\Block;

use Drupal\calendar\GoogleGateway;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *     id = "events_block",
 *     admin_label = "Upcoming Events",
 *     context = {
 *         "node" = @ContextDefinition("entity:node")
 *     }
 * )
 */
class EventsBlock extends BlockBase implements BlockPluginInterface
{
    const DEFAULT_FIELDNAME = 'field_calendar_id';
    const DEFAULT_NUMDAYS   = 7;
    const DEFAULT_MAXEVENTS = 4;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $config = $this->getConfiguration();
        $node   = $this->getContextValue('node');

        $fieldname = !empty($config['fieldname']) ?      $config['fieldname'] : self::DEFAULT_FIELDNAME;
        $numdays   = !empty($config['numdays'  ]) ? (int)$config['numdays'  ] : self::DEFAULT_NUMDAYS;
        $maxevents = !empty($config['maxevents']) ? (int)$config['maxevents'] : self::DEFAULT_MAXEVENTS;

        if ($node->hasField( $fieldname)) {
            $id = $node->get($fieldname)->value;
            if ($id) {
                $start = new \DateTime();
                $end   = new \DateTime();
                $end->add(new \DateInterval("P{$numdays}D"));

                $events  = GoogleGateway::events($id, $start, $end);
                $display = [];
                $count   = 0;
                foreach ($events as $e) {
                    if (++$count > $maxevents) { break; }

                    $display[] = $e;
                }

                return [
                    '#theme'      => 'calendar_events',
                    '#events'     => $display,
                    '#calendarId' => $id
                ];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $form   = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();

        $form['events_block_fieldname'] = [
            '#type'          => 'textfield',
            '#title'         => 'Fieldname',
            '#description'   => 'Name of the node field that contains the Google Calendar ID',
            '#default_value' => isset($config['fieldname']) ? $config['fieldname'] : ''
        ];
        $form['events_block_numdays'] = [
            '#type'          => 'number',
            '#title'         => 'Number of days',
            '#description'   => 'Maximum number of days in the future to look for events.',
            '#default_value' => isset($config['numdays']) ? $config['numdays'] : self::DEFAULT_NUMDAYS
        ];
        $form['events_block_maxevents'] = [
            '#type'          => 'number',
            '#title'         => 'Max Events',
            '#description'   => 'Maximum number of events to show in the block',
            '#default_value' => isset($config['maxevents']) ? $config['maxevents'] : self::DEFAULT_MAXEVENTS
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['fieldname'] = $form_state->getValue('events_block_fieldname');
        $this->configuration['numdays'  ] = $form_state->getValue('events_block_numdays'  );
        $this->configuration['maxevents'] = $form_state->getValue('events_block_maxevents');
    }
}
