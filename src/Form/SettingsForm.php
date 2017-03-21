<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\calendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'calendar_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames()
    {
        return ['calendar.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('calendar.settings');

        $form['google_user_email'] = [
            '#type'          => 'textfield',
            '#title'         => 'Google User Email',
            '#default_value' => $config->get('google_user_email')
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('calendar.settings')
             ->set('google_user_email', $form_state->getValue('google_user_email'))
             ->save();

        parent::submitForm($form, $form_state);
    }
}
