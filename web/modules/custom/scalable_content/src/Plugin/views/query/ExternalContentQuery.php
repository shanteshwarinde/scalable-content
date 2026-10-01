<?php

namespace Drupal\scalable_content\Plugin\views\query;

use Drupal\views\Annotation\ViewsQuery;
use Drupal\views\Plugin\views\query\Sql;
use Drupal\views\ViewExecutable;

/**
 * Custom SQL query plugin for External Content.
 *
 * @ViewsQuery(
 *   id = "scalable_content_sql",
 *   title = @Translation("Scalable Content SQL"),
 *   help = @Translation("Custom SQL query handler for External Content Views.")
 * )
 */
class ExternalContentQuery extends Sql {

  /**
   * {@inheritdoc}
   */
  public function build(ViewExecutable $view) {
    // Let the parent class build the SQL query.
    parent::build($view);
  }

}
