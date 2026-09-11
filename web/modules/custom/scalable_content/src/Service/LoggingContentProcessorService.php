<?php

namespace Drupal\scalable_content\Service;

/**
 * Decorates the content processor service.
 */
class LoggingContentProcessorService implements ContentProcessorInterface {
  /**
   * The decorated service.
   *
   * @var \Drupal\scalable_content\Service\ContentProcessorService
   */
  protected ContentProcessorService $inner;

  /**
   * Constructs the decorator.
   *
   * @param \Drupal\scalable_content\Service\ContentProcessorService $inner
   *   The original content processor service.
   */
  public function __construct(ContentProcessorService $inner) {
    $this->inner = $inner;
  }

  /**
   * Processes content through the decorated service.
   *
   * @param array $data
   *   The content data.
   *
   * @return array
   *   The processed content data.
   */
  public function process(array $data): array {
    \Drupal::logger('scalable_content')->info(
      'Content processor service processed @count fields.',
      [
        '@count' => count($data),
      ]
    );

    return $this->inner->process($data);
  }

}
