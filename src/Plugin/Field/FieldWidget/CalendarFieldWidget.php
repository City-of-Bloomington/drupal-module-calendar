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
