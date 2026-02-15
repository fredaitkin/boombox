<?php

namespace Drupal\boombox_song\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SongAddForm extends FormBase {

  public function getFormId(): string {
    return 'boombox_song_add_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Song title'),
      '#required' => TRUE,
      '#maxlength' => 255,
    ];

    $artists = \Drupal::database()
      ->select('boombox_artist', 'a')
      ->fields('a', ['id', 'name'])
      ->orderBy('a.name', 'ASC')
      ->execute()
      ->fetchAllKeyed();

    $artist_options = [0 => $this->t('- None -')];
    foreach ($artists as $id => $name) {
      $artist_options[(int) $id] = $name;
    }

    $form['artist_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Artist'),
      '#options' => $artist_options,
      '#default_value' => 0,
    ];

    $form['duration_seconds'] = [
      '#type' => 'number',
      '#title' => $this->t('Duration (seconds)'),
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => 0,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save song'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $duration = (int) $form_state->getValue('duration_seconds');
    if ($duration < 0) {
      $form_state->setErrorByName('duration_seconds', $this->t('Duration must be zero or greater.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $timestamp = \Drupal::time()->getRequestTime();
    $artist_id_value = (int) $form_state->getValue('artist_id');

    \Drupal::database()->insert('boombox_song')
      ->fields([
        'title' => trim((string) $form_state->getValue('title')),
        'artist_id' => $artist_id_value > 0 ? $artist_id_value : NULL,
        'duration_seconds' => (int) $form_state->getValue('duration_seconds'),
        'created' => $timestamp,
        'changed' => $timestamp,
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('Song saved.'));
    $form_state->setRedirect('boombox_app.songs');
  }

}
