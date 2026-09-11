<?php

namespace Drupal\scalable_content\Plugin\Attribute;

use Drupal\Component\Plugin\Attribute\Plugin;

/**
 * Defines the Content Processor plugin attribute.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class ContentProcessor extends Plugin {

  /**
   * Constructs a Content Processor attribute.
   *
   * @param string $id
   *   The plugin ID.
   * @param string $label
   *   The human-readable plugin label.
   */
  public function __construct(
    string $id,
    public readonly string $label,
  ) {
    parent::__construct($id);
  }

}
