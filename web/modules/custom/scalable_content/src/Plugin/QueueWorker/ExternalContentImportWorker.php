<?php

namespace Drupal\scalable_content\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\scalable_content\Service\ContentProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Processes external content import records.
 *
 * @QueueWorker(
 *   id = "scalable_content_import",
 *   title = @Translation("Scalable Content Import"),
 *   cron = {"time" = 30}
 * )
 */
class ExternalContentImportWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * Entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * Content processor.
   *
   * @var \Drupal\scalable_content\Service\ContentProcessorInterface
   */
  protected ContentProcessorInterface $contentProcessor;

  /**
   * Constructs the queue worker.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    ContentProcessorInterface $content_processor,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->storage = $entity_type_manager->getStorage('external_content');
    $this->contentProcessor = $content_processor;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('scalable_content.processor'),
    );
  }

  /**
   * Processes one imported record.
   *
   * @param array $data
   *   The external content record.
   */
  public function processItem($data): void {
    if (empty($data['external_id']) || empty($data['title'])) {
      return;
    }

    $data = $this->contentProcessor->process($data);

    $entity = $this->storage->create([
      'external_id' => $data['external_id'],
      'title' => $data['title'],
      'description' => $data['description'] ?? '',
      'category' => $data['category'] ?? '',
      'source' => $data['source'] ?? 'external',
      'status' => TRUE,
    ]);

    $entity->save();
  }

  /**
 * Processes multiple queue items.
 *
 * @param int $limit
 *   Maximum number of queue items to process.
 *
 * @return int
 *   Number of processed items.
 */
public function processQueue(int $limit = 0): int {
  $queue = \Drupal::service('queue')
    ->get('scalable_content_import');

  $processed = 0;

  while (($item = $queue->claimItem(30)) !== FALSE) {
    try {
      $this->processItem($item->data);
      $queue->deleteItem($item);

      $processed++;

      if ($limit > 0 && $processed >= $limit) {
        break;
      }
    }
    catch (\Throwable $exception) {
      $queue->releaseItem($item);

      \Drupal::logger('scalable_content')->error(
        'Queue item processing failed: @message',
        [
          '@message' => $exception->getMessage(),
        ]
      );
    }
  }

  return $processed;
}

}
