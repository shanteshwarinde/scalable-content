<?php

namespace Drupal\scalable_content\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for External Content.
 */
class ExternalContentForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $entity = $this->getEntity();

    $status = $entity->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addStatus(
        $this->t('External content %label has been created.', [
          '%label' => $entity->label(),
        ])
      );
    }
    else {
      $this->messenger()->addStatus(
        $this->t('External content %label has been updated.', [
          '%label' => $entity->label(),
        ])
      );
    }

    $form_state->setRedirect(
      'entity.external_content.canonical',
      ['external_content' => $entity->id()]
    );

    return $status;
  }

}
