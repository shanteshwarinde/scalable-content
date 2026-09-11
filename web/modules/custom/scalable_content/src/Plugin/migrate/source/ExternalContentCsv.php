<?php

namespace Drupal\scalable_content\Plugin\migrate\source;

use Drupal\migrate\Attribute\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Provides a CSV source for external content.
 */
#[MigrateSource(
  id: 'external_content_csv',
)]
class ExternalContentCsv extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'external_id' => [
        'type' => 'string',
      ],
    ];
  }

  /**
 * {@inheritdoc}
 */
public function __toString(): string {
  return 'External Content CSV';
}

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'external_id' => 'External ID',
      'title' => 'Title',
      'description' => 'Description',
      'category' => 'Category',
      'source' => 'Source',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator(): \Iterator {
    $file_path = $this->configuration['file'];

    if (!is_readable($file_path)) {
      throw new \InvalidArgumentException(
        sprintf('CSV file is not readable: %s', $file_path)
      );
    }

    $handle = fopen($file_path, 'rb');

    if ($handle === FALSE) {
      throw new \RuntimeException(
        sprintf('Unable to open CSV file: %s', $file_path)
      );
    }

    $headers = fgetcsv($handle);

    if ($headers === FALSE) {
      fclose($handle);
      return new \ArrayIterator([]);
    }

    return $this->createIterator($handle, $headers);
  }

  /**
   * Creates a lazy iterator over CSV rows.
   */
  protected function createIterator($handle, array $headers): \Generator {
    try {
      while (($row = fgetcsv($handle)) !== FALSE) {
        if (count($row) !== count($headers)) {
          continue;
        }

        $record = array_combine($headers, $row);

        if ($record === FALSE) {
          continue;
        }

        yield $record;
      }
    }
    finally {
      fclose($handle);
    }
  }

}
