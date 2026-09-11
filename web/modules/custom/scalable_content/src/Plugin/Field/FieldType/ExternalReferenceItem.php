#[FieldType(
  id: "external_reference",
  label: new TranslatableMarkup("External Reference"),
  description: new TranslatableMarkup("Stores external reference information."),
  category: "custom",
  default_widget: "external_reference_default",
  default_formatter: "external_reference_default"
)]
class ExternalReferenceItem extends FieldItemBase {

  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['external_id'] = DataDefinition::create('string')
      ->setLabel(t('External ID'));

    $properties['source'] = DataDefinition::create('string')
      ->setLabel(t('Source'));

    $properties['url'] = DataDefinition::create('uri')
      ->setLabel(t('URL'));

    return $properties;
  }
}
