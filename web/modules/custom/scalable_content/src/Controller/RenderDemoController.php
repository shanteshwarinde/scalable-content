<?php

namespace Drupal\scalable_content\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Demonstrates Drupal Render API usage.
 */
class RenderDemoController extends ControllerBase {

  /**
   * Builds a render array.
   */
  public function demo(): array {
    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['scalable-content-demo'],
      ],
      'title' => [
        '#markup' => '<h2>Scalable Content Platform</h2>',
      ],
      'message' => [
        '#markup' => '<p>Render API works.</p>',
      ],
    ];
  }

}
