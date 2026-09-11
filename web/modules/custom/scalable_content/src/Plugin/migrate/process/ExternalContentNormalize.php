<?php

namespace Drupal\scalable_content\Plugin\migrate\process;

use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Trims whitespace from a migrated string value.
 */
#[MigrateProcess(
  id: 'external_content_normalize',
)]
class ExternalContentNormalize extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform(
    $value,
    MigrateExecutableInterface $migrate_executable,
    Row $row,
    $destination_property
  ) {
    if (is_string($value)) {
      return trim($value);
    }

    return $value;
  }

}
