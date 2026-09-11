<?php

namespace Drupal\scalable_content\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;

/**
 * Configuration form for Scalable Content.
 */
class ScalableContentSettingsForm extends ConfigFormBase {

  /**
   * The Content Processor plugin manager.
   *
   * @var \Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager
   */
  protected ContentProcessorManager $processorManager;

  /**
   * Constructs the settings form.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\scalable_content\Plugin\ContentProcessor\ContentProcessorManager $processor_manager
   *   The content processor plugin manager.
   */
  public function __construct(
  ConfigFactoryInterface $config_factory,
  TypedConfigManagerInterface $typed_config_manager,
  ContentProcessorManager $processor_manager,
) {
  parent::__construct($config_factory, $typed_config_manager);
  $this->processorManager = $processor_manager;
}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
  $container->get('config.factory'),
  $container->get('config.typed'),
  $container->get('plugin.manager.scalable_content_processor'),
);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'scalable_content_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'scalable_content.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
  ): array {
    $config = $this->config('scalable_content.settings');

    $definitions = $this->processorManager->getDefinitions();

    $options = [];
    foreach ($definitions as $plugin_id => $definition) {
      $options[$plugin_id] = $definition['label'] ?? $plugin_id;
    }

    $processor_id = $config->get('processor') ?: 'normalize';

    $processor_configuration = $config->get('processor_configuration') ?: [
      'trim_strings' => TRUE,
    ];

    $form['processor'] = [
      '#type' => 'select',
      '#title' => $this->t('Content processor'),
      '#description' => $this->t('Select the plugin used to process external content.'),
      '#options' => $options,
      '#default_value' => $processor_id,
      '#required' => TRUE,
    ];

    if ($this->processorManager->hasDefinition($processor_id)) {
      $processor = $this->processorManager->createInstance(
        $processor_id,
        $processor_configuration,
      );

      if ($processor instanceof PluginFormInterface) {
        $form['processor_configuration'] = [
          '#type' => 'details',
          '#title' => $this->t('Processor configuration'),
          '#open' => TRUE,
        ];

        $form['processor_configuration'] += $processor->buildConfigurationForm(
          [],
          $form_state,
        );
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    $processor_id = $form_state->getValue('processor');

    $processor_configuration = [
      'trim_strings' => (bool) $form_state->getValue(
        'trim_strings',
      ),
    ];

    $this->configFactory
      ->getEditable('scalable_content.settings')
      ->set('processor', $processor_id)
      ->set('processor_configuration', $processor_configuration)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
