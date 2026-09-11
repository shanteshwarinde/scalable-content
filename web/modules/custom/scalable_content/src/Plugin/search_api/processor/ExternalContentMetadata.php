<?php

namespace Drupal\scalable_content\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds combined external content metadata to the search index.
 *
 * @SearchApiProcessor(
 *   id = "scalable_external_content_metadata",
 *   label = @Translation("External Content Metadata"),
 *   description = @Translation("Adds a combined category and source value to the search index."),
 *   stages = {
 *     "add_properties" = 0
 *   }
 * )
 */
class ExternalContentMetadata extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(
    ?DatasourceInterface $datasource = NULL
  ) {
    $properties = [];

    if ($datasource) {
      if ($datasource->getEntityTypeId() !== 'external_content') {
        return $properties;
      }
    }

    $properties['scalable_external_content_metadata'] = new ProcessorProperty([
      'label' => $this->t('External Content Metadata'),
      'description' => $this->t(
        'Combined category and source information.'
      ),
      'type' => 'string',
      'is_list' => FALSE,
      'processor_id' => $this->getPluginId(),
    ]);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $object = $item->getOriginalObject()->getValue();

    if (!$object || $object->getEntityTypeId() !== 'external_content') {
      return;
    }

    $category = $object->get('category')->value ?? '';
    $source = $object->get('source')->value ?? '';

    $metadata = trim($category . ' | ' . $source);

    if ($metadata === '|') {
      return;
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath(
        $item->getFields(),
        NULL,
        'scalable_external_content_metadata'
      );

    foreach ($fields as $field) {
      $field->addValue($metadata);
    }
  }

}
