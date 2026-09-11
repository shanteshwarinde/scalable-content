<?php

namespace Drupal\scalable_content\Service;

use Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager;

/**
 * Provides content processing functionality.
 */
class ContentProcessorService implements ContentProcessorInterface {
  /**
   * The content processor plugin manager.
   *
   * @var \Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager
   */
  protected ContentProcessorManager $processorManager;

  /**
   * Constructs the ContentProcessorService.
   *
   * @param \Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager $processor_manager
   *   The content processor plugin manager.
   */
  public function __construct(
    ContentProcessorManager $processor_manager,
  ) {
    $this->processorManager = $processor_manager;
  }

  /**
   * Processes content using the configured processor.
   *
   * @param array $data
   *   The content data.
   *
   * @return array
   *   The processed content data.
   */
  public function process(array $data): array {
    $config = \Drupal::config('scalable_content.settings');

    $processor_id = $config->get('processor') ?: 'normalize';
    $processor_configuration = $config->get('processor_configuration') ?: [];

    $processor = $this->processorManager->createInstance(
      $processor_id,
      $processor_configuration,
    );

    return $processor->process($data);
  }

}
