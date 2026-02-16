<?php

namespace Drupal\boombox_artist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class ArtistForm extends ContentEntityForm {

  public function save(array $form, FormStateInterface $form_state): int {
    $is_new = $this->entity->isNew();
    $status = parent::save($form, $form_state);

    if ($is_new) {
      $this->messenger()->addStatus($this->t('Artist created.'));
    }
    else {
      $this->messenger()->addStatus($this->t('Artist updated.'));
    }

    $form_state->setRedirect('entity.boombox_artist.collection');

    return $status;
  }

}
