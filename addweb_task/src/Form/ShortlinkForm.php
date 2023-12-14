<?php

namespace Drupal\addweb_task\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\Core\Url;

class ShortlinkForm extends FormBase {

  public function getFormId() {
    return 'shortlink_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
//  public function validateForm(array &$form, FormStateInterface $form_state) {
//    $url = $form_state->getValue('url');
//
//    // Validate the URL.
//    if (!Url::fromUri($url, ['absolute' => TRUE])->isRouted()) {
//      $form_state->setErrorByName('url', $this->t('Invalid URL. Please enter a valid absolute URL.'));
//    }
//  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get form values.
    $url = $form_state->getValue('url');

    // Convert URL to short link.
    $shortlink = $this->generateShortlink($url);

    // Save data to the database.
    $this->saveToDatabase($url, $shortlink);

    // Set a success message.
    $this->messenger()->addMessage($this->t('URL has been shortened successfully.'));

    // Redirect to the short URL list page.
    $form_state->setRedirect('addweb_task.short_url_list');
  }

  private function generateShortlink($url) {
    $shortlink = $url;

    return $shortlink;
  }

  private function saveToDatabase($url, $shortlink) {
    // Save data to the database.
    $database = \Drupal::database();
    $database->insert('shortlink_data')
      ->fields([
        'url' => $url,
        'shortlink' => $shortlink,
        'created' => \Drupal::time()->getRequestTime(),
      ])
      ->execute();

    // Provide a message to the user.
    \Drupal::messenger()->addStatus($this->t('Data saved successfully.'));
  }
}
