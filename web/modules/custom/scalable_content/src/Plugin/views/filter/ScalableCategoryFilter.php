<?php

namespace Drupal\scalable_content\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\StringFilter;

/**
 * Provides a custom category filter for External Content.
 *
 * @ViewsFilter("scalable_category_filter")
 */
class ScalableCategoryFilter extends StringFilter {

  /**
   * {@inheritdoc}
   */
  public function query(): void {
    $this->ensureMyTable();

    $field = $this->tableAlias . '.' . $this->realField;

    $this->query->addWhere(
      $this->options['group'],
      $field,
      $this->value,
      $this->operator
    );
  }

}
