<?php

namespace Drupal\scalable_content\Plugin\ContentProcessor;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Content Processor plugins.
 */
interface ContentProcessorInterface extends PluginInspectionInterface {

  /**
   * Processes external content.
   *
   * @param array $data
   *   The content data.
   *
   * @return array
   *   The processed content data.
   */
  public function process(array $data): array;

}
