<?php

namespace Drupal\scalable_content\Service;

interface ContentProcessorInterface {

  /**
   * Processes content data.
   *
   * @param array $data
   *   The content data.
   *
   * @return array
   *   The processed content data.
   */
  public function process(array $data): array;

}
