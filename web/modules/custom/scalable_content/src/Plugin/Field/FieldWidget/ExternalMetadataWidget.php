<?php

namespace Drupal\scalable_content\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Attribute\FieldWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a widget for the External Metadata field.
 */
#[FieldWidget(
  id: 'scalable_external_metadata_widget',
  label: new TranslatableMarkup('External Metadata'),
  field_types: [
    'scalable_external_metadata',
  ],
)]
class ExternalMetadataWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta,
    array $element,
    array &$form,
    FormStateInterface $form_state
  ) {
    $item = $items[$delta];

    $element['source_system'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source System'),
      '#default_value' => $item->source_system ?? '',
      '#maxlength' => 100,
      '#required' => TRUE,
    ];

    $element['external_reference'] = [
      '#type' => 'textfield',
      '#title' => $this->t('External Reference'),
      '#default_value' => $item->external_reference ?? '',
      '#maxlength' => 255,
      '#required' => TRUE,
    ];

    $element['checksum'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Checksum'),
      '#default_value' => $item->checksum ?? '',
      '#maxlength' => 64,
      '#required' => FALSE,
    ];

    return $element;
  }

}
