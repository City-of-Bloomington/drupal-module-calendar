<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\calendar\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type of baz.
 *
 * @FieldType(
 *   id = "calendar_calendar",
 *   label = "Google Calendar",
 *   default_formatter = "calendar_eventslist",
 *   default_widget = "calendar_calendarfield",
 * )
 */
class CalendarItem extends FieldItemBase
{
    public static function schema(FieldStorageDefinitionInterface $field_definition)
    {
        return [
            'columns' => [
                'calendarId' => [
                    'type'     => 'varchar',
                    'length'   => 128,
                    'not null' => true
                ]
            ]
        ];
    }

    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
    {
        $properties['calendarId'] = DataDefinition::create('string')->setLabel('Calendar ID');
        return $properties;
    }

    public function isEmpty()
    {
        $value = $this->get('calendarId')->getValue();
        return $value === null || $value === '';
    }
}
