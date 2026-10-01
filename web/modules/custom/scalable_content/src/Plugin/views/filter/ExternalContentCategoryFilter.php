<?php

namespace Drupal\scalable_content\Plugin\views\filter;

use Drupal\views\Attribute\ViewsFilter;
use Drupal\views\Plugin\views\filter\StringFilter;

/**
 * Provides a custom category filter for External Content.
 */
#[ViewsFilter('scalable_external_content_category')]
class ExternalContentCategoryFilter extends StringFilter {

  /**
   * {@inheritdoc}
   */
  public function query(): void {
    $this->ensureMyTable();

    if ($this->value === '' || $this->value === NULL) {
      return;
    }

    $field = $this->tableAlias . '.' . $this->realField;

    $this->query->addWhere(
      $this->options['group'],
      $field,
      $this->value,
      '='
    );
  }

}
