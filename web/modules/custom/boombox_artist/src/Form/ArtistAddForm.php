<?php

namespace Drupal\boombox_artist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ArtistAddForm extends FormBase {

  public function getFormId(): string {
    return 'boombox_artist_add_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Artist name'),
      '#required' => TRUE,
      '#maxlength' => 255,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save artist'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $timestamp = \Drupal::time()->getRequestTime();
    \Drupal::database()->insert('boombox_artist')
      ->fields([
        'name' => trim((string) $form_state->getValue('name')),
        'created' => $timestamp,
        'changed' => $timestamp,
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('Artist saved.'));
    $form_state->setRedirect('boombox_app.artists');
  }

}
