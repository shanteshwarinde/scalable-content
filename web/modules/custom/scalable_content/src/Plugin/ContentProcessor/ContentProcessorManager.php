<?php

namespace Drupal\scalable_content\Plugin\ContentProcessor;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages Content Processor plugins.
 */
class ContentProcessorManager extends DefaultPluginManager {

  /**
   * Constructs the Content Processor plugin manager.
   *
   * @param \Traversable $namespaces
   *   The available namespaces.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
  ) {
    parent::__construct(
      'Plugin/ContentProcessor',
      $namespaces,
      $module_handler,
      ContentProcessorInterface::class,
      \Drupal\scalable_content\Plugin\Attribute\ContentProcessor::class,
    );

    $this->setCacheBackend(
      $cache_backend,
      'scalable_content_content_processor_plugins'
    );

    $this->alterInfo('scalable_content_content_processor_info');
  }

}
