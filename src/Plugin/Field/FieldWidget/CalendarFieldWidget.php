<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\calendar\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form input for a Google Calendar ID.
 *
 * @FieldWidget(
 *   id = "calendar_calendarfield",
 *   label = "Calendar Field",
 *   field_types = {
 *     "calendar_calendar"
 *   }
 * )
 */
class CalendarFieldWidget extends WidgetBase
{
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
    {
        $element['calendarId'] = $element + [
            '#type'          => 'textfield',
            '#default_value' => isset($items[$delta]->calendarId) ? $items[$delta]->calendarId : null,
            '#maxLength'     => $this->getFieldSetting('max_length')
        ];
        return $element;
    }
}
