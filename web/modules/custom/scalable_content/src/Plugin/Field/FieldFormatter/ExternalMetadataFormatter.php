<?php

namespace Drupal\scalable_content\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a formatter for the External Metadata field.
 */
#[FieldFormatter(
  id: 'scalable_external_metadata_formatter',
  label: new TranslatableMarkup('External Metadata'),
  field_types: [
    'scalable_external_metadata',
  ],
)]
class ExternalMetadataFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(
    FieldItemListInterface $items,
    $langcode
  ) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'item_list',
        '#items' => [
          $this->t('Source System: @source', [
            '@source' => $item->source_system,
          ]),
          $this->t('External Reference: @reference', [
            '@reference' => $item->external_reference,
          ]),
          $this->t('Checksum: @checksum', [
            '@checksum' => $item->checksum,
          ]),
        ],
      ];
    }

    return $elements;
  }

}
