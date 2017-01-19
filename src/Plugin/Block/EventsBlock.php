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
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $config = $this->getConfiguration();
        $node   = $this->getContextValue('node');

        $fieldname = !empty($config['fieldname'])
                          ? $config['fieldname']
                          : 'field_committee';

        if ($node->hasField( $fieldname)) {
            $id = $node->get($fieldname)->value;
            if ($id) {
                $start = new \DateTime();
                $end   = new \DateTime();
                $end->add(new \DateInterval('P7D'));

                $events = GoogleGateway::events($id, $start, $end);

                return [
                    '#theme'  => 'calendar_events',
                    '#events' => $events
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
            '#type' => 'textfield',
            '#title' => 'Fieldname',
            '#description' => 'Name of the node field that contains the Google Calendar ID',
            '#default_value' => isset($config['fieldname']) ? $config['fieldname'] : ''
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['fieldname'] = $form_state->getValue('events_block_fieldname');
    }
}
