<?php

namespace Drupal\scalable_content\Service;

use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class ExternalContentImporter {

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * The content processor.
   *
   * @var \Drupal\scalable_content\Service\ContentProcessorService
   */
  protected ContentProcessorInterface $contentProcessor;

  /**
 * Queue factory.
 *
 * @var \Drupal\Core\Queue\QueueFactory
 */
protected QueueFactory $queueFactory;
  /**
   * Constructs the importer.
   */
public function __construct(
  EntityTypeManagerInterface $entity_type_manager,
  ContentProcessorInterface $content_processor,
  QueueFactory $queue_factory,
) {
  $this->storage = $entity_type_manager->getStorage('external_content');
  $this->contentProcessor = $content_processor;
  $this->queueFactory = $queue_factory;
}

/**
 * Imports records from a CSV file using streaming and queues them.
 *
 * @param string $file_path
 *   Absolute path to the CSV file.
 * @param int $limit
 *   Maximum number of records to queue. 0 means unlimited.
 *
 * @return int
 *   Number of queued records.
 */
public function importCsv(string $file_path, int $limit = 0): int {
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
    return 0;
  }

  $queue = $this->queueFactory->get('scalable_content_import');
  $count = 0;

  while (($row = fgetcsv($handle)) !== FALSE) {
    if ($limit > 0 && $count >= $limit) {
      break;
    }

    if (count($row) !== count($headers)) {
      continue;
    }

    $record = array_combine($headers, $row);

    if (!$record) {
      continue;
    }

    if (empty($record['external_id']) || empty($record['title'])) {
      continue;
    }

    // Add the record to the queue.
    $queue->createItem($record);

    $count++;
  }

  fclose($handle);

  return $count;
}
}
