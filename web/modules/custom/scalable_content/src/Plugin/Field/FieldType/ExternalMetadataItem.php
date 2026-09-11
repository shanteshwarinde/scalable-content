<?php

namespace Drupal\scalable_content\Plugin\Field\FieldType;

use Drupal\Core\Field\Attribute\FieldType;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides an External Metadata field type.
 */
#[FieldType(
  id: 'scalable_external_metadata',
  label: new TranslatableMarkup('External Metadata'),
  description: new TranslatableMarkup('Stores external source metadata including source system, reference ID, and checksum.'),
  category: new TranslatableMarkup('Scalable Content'),
  default_widget: 'scalable_external_metadata_widget',
  default_formatter: 'scalable_external_metadata_formatter',
)]
class ExternalMetadataItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(
    FieldStorageDefinitionInterface $field_definition
  ) {
    $properties = [];

    $properties['source_system'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Source System'));

    $properties['external_reference'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('External Reference'));

    $properties['checksum'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Checksum'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(
    FieldStorageDefinitionInterface $field_definition
  ) {
    return [
      'columns' => [
        'source_system' => [
          'type' => 'varchar',
          'length' => 100,
          'not null' => FALSE,
        ],
        'external_reference' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ],
        'checksum' => [
          'type' => 'varchar',
          'length' => 64,
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return empty($this->source_system)
      && empty($this->external_reference)
      && empty($this->checksum);
  }

}
