<?php

namespace Drupal\scalable_content\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\EntityOwnerTrait;
use Drupal\Core\Entity\ContentEntityBase;

/**
 * Defines the External Content entity.
 *
 * @ContentEntityType(
 *   id = "external_content",
 *   label = @Translation("External Content"),
 *   label_collection = @Translation("External Content"),
 *   label_singular = @Translation("external content"),
 *   label_plural = @Translation("external content"),
 *   label_count = @PluralTranslation(
 *     singular = "@count external content",
 *     plural = "@count external content",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\scalable_content\ExternalContentListBuilder",
 *     "access" = "Drupal\scalable_content\ExternalContentAccessControlHandler",
 *     "views_data" = "Drupal\scalable_content\ExternalContentViewsData",
 *     "form" = {
 *       "add" = "Drupal\scalable_content\Form\ExternalContentForm",
 *       "edit" = "Drupal\scalable_content\Form\ExternalContentForm",
 *       "delete" = "Drupal\scalable_content\Form\ExternalContentDeleteForm"
 *     }
 *   },
 *   base_table = "external_content",
 *   data_table = "external_content_field_data",
 *   revision_table = "external_content_revision",
 *   revision_data_table = "external_content_field_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   admin_permission = "administer external content",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "published" = "status"
 *   },
 *   links = {
 *     "canonical" = "/external-content/{external_content}",
 *     "add-form" = "/external-content/add",
 *     "edit-form" = "/external-content/{external_content}/edit",
 *     "delete-form" = "/external-content/{external_content}/delete",
 *     "collection" = "/admin/content/external-content"
 *   }
 * )
 */
class ExternalContent extends ContentEntityBase {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * Gets the external ID.
   */
  public function getExternalId(): string {
    return (string) $this->get('external_id')->value;
  }

  /**
   * Gets the title.
   */
  public function getTitle(): string {
    return (string) $this->get('title')->value;
  }

  /**
   * Sets the title.
   */
  public function setTitle(string $title): static {
    $this->set('title', $title);
    return $this;
  }

  /**
   * Gets the description.
   */
  public function getDescription(): string {
    return (string) $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Title'))
      ->setDescription(new TranslatableMarkup('The title of the external content.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -10,
      ]);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(new TranslatableMarkup('Description'))
      ->setDescription(new TranslatableMarkup('Description of the external content.'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 0,
      ]);

    $fields['external_id'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('External ID'))
      ->setDescription(new TranslatableMarkup('Unique identifier from the external source.'))
      ->setRequired(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 128)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 10,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 10,
      ]);

    $fields['category'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Category'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 128)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 20,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 20,
      ]);

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Source'))
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 128)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 30,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 30,
      ]);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(new TranslatableMarkup('Published'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 40,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 40,
      ]);

    $fields['external_metadata'] = BaseFieldDefinition::create('scalable_external_metadata')
  ->setLabel(new TranslatableMarkup('External Metadata'))
  ->setDescription(new TranslatableMarkup('Metadata received from the external source.'))
  ->setCardinality(1)
  ->setRevisionable(TRUE)
  ->setTranslatable(FALSE)
  ->setDisplayOptions('form', [
    'type' => 'scalable_external_metadata_widget',
    'weight' => 50,
  ])
  ->setDisplayOptions('view', [
    'label' => 'above',
    'type' => 'scalable_external_metadata_formatter',
    'weight' => 50,
  ]);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(new TranslatableMarkup('Author'))
      ->setSetting('target_type', 'user')
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(new TranslatableMarkup('Created'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(new TranslatableMarkup('Changed'));

    return $fields;
  }

}
