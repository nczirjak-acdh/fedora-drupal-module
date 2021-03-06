<?php
/**
 * @file
 * Contains \Drupal\oeaw\AddForm.
 */

namespace Drupal\oeaw;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;

class AddForm extends FormBase {
  protected $id;

  function getFormId() {
    return 'oeaw_add';
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $this->id = \Drupal::request()->get('id');
    $oeaw = oeawStorage::get($this->id);

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => ($oeaw) ? $oeaw->name : '',
    );
    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => t('Message'),
      '#default_value' => ($oeaw) ? $oeaw->message : '',
    );
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => ($oeaw) ? t('Edit') : t('Add'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
    $message = $form_state->getValue('message');
    if (!empty($this->id)) {
      oeawStorage::edit($this->id, SafeMarkup::checkPlain($name), SafeMarkup::checkPlain($message));
      \Drupal::logger('oeaw')->notice('@type: deleted %title.',
          array(
              '@type' => $this->id,
              '%title' => $this->id,
          ));

      drupal_set_message(t('Your message has been edited'));
    }
    else {
      oeawStorage::add(SafeMarkup::checkPlain($name), SafeMarkup::checkPlain($message));
      \Drupal::logger('oeaw')->notice('@type: deleted %title.',
          array(
              '@type' => $this->id,
              '%title' => $this->id,
          ));

      drupal_set_message(t('Your message has been submitted'));
    }
    $form_state->setRedirect('oeaw_list');
    return;
  }
}
