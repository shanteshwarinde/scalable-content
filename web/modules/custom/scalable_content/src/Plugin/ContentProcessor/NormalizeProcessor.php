<?php

namespace Drupal\scalable_content\Plugin\ContentProcessor;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\scalable_content\Plugin\Attribute\ContentProcessor;

/**
 * Normalizes external content before it is saved.
 */
#[ContentProcessor(
  id: 'normalize',
  label: 'Normalize Content',
)]
class NormalizeProcessor extends PluginBase implements ContentProcessorInterface, PluginFormInterface {

  use StringTranslationTrait;
  /**
   * Whether string values should be trimmed.
   *
   * @var bool
   */
  protected bool $trim_strings = TRUE;
  /**
 * Constructs the NormalizeProcessor plugin.
 *
 * @param array $configuration
 *   A configuration array containing information about the plugin instance.
 * @param string $plugin_id
 *   The plugin ID.
 * @param mixed $plugin_definition
 *   The plugin implementation definition.
 */
public function __construct(
  array $configuration,
  $plugin_id,
  $plugin_definition,
) {
  parent::__construct($configuration, $plugin_id, $plugin_definition);
  $this->setConfiguration($configuration);
}
  /**
   * {@inheritdoc}
   */
  public function process(array $data): array {
    if (!$this->trim_strings) {
      return $data;
    }

    foreach ($data as $key => $value) {
      if (is_string($value)) {
        $data[$key] = trim($value);
      }
    }

    return $data;
  }

  /**
   * Builds the plugin configuration form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The configuration form.
   */
  public function buildConfigurationForm(
    array $form,
    FormStateInterface $form_state,
  ): array {
    $form['trim_strings'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Trim whitespace from string values'),
      '#default_value' => $this->trim_strings,
    ];

    return $form;
  }

  /**
 * Validates the plugin configuration form.
 *
 * @param array $form
 *   The form array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
public function validateConfigurationForm(
  array &$form,
  FormStateInterface $form_state,
): void {
  // No additional validation is required.
}

  /**
   * Submits the plugin configuration form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function submitConfigurationForm(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    $this->trim_strings = (bool) $form_state->getValue('trim_strings');
  }

  /**
   * Returns the plugin configuration.
   *
   * @return array
   *   The configuration.
   */
  public function getConfiguration(): array {
    return [
      'trim_strings' => $this->trim_strings,
    ];
  }

  /**
   * Sets the plugin configuration.
   *
   * @param array $configuration
   *   The configuration.
   */
  public function setConfiguration(array $configuration): void {
    $this->trim_strings = isset($configuration['trim_strings'])
      ? (bool) $configuration['trim_strings']
      : TRUE;
  }

}
