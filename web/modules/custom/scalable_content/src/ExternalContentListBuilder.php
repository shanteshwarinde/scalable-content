<?php

namespace Drupal\scalable_content;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list of External Content entities.
 */
class ExternalContentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['title'] = $this->t('Title');
    $header['external_id'] = $this->t('External ID');
    $header['category'] = $this->t('Category');
    $header['status'] = $this->t('Status');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['title'] = $entity->toLink();
    $row['external_id'] = $entity->get('external_id')->value;
    $row['category'] = $entity->get('category')->value;
    $row['status'] = $entity->get('status')->value
      ? $this->t('Published')
      : $this->t('Unpublished');

    return $row + parent::buildRow($entity);
  }

}
