<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
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

                $events = GoogleGateway::events($id, $start, $end);
                foreach ($events as $e) {
                    $n = [];


                }

                return [
                    '#theme'      => 'calendar_events',
                    '#events'     => $events,
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
