<?php

/**
 * @file
 * Contains \Drupal\Core\Entity\DynamicallyFieldableEntityStorageInterface.
 */

namespace Drupal\Core\Entity;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionListenerInterface;

/**
 * A storage that supports entity types with dynamic field definitions.
 *
 * A storage that implements this interface can react to the entity type's field
 * definitions changing, due to modules being installed or uninstalled, or via
 * field UI, or via code changes to the entity class.
 *
 * For example, configurable fields defined and exposed by field.module.
 */
interface DynamicallyFieldableEntityStorageInterface extends FieldableEntityStorageInterface, FieldStorageDefinitionListenerInterface {

  /**
   * Determines if the storage contains any data.
   *
   * @return bool
   *   TRUE if the storage contains data, FALSE if not.
   */
  public function hasData();

  /**
   * Reacts to the creation of a field.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition created.
   */
  public function onFieldDefinitionCreate(FieldDefinitionInterface $field_definition);

  /**
   * Reacts to the update of a field.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition being updated.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $original
   *   The original field definition; i.e., the definition before the update.
   */
  public function onFieldDefinitionUpdate(FieldDefinitionInterface $field_definition, FieldDefinitionInterface $original);

  /**
   * Reacts to the deletion of a field.
   *
   * Stored values should not be wiped at once, but marked as 'deleted' so that
   * they can go through a proper purge process later on.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition being deleted.
   *
   * @see purgeFieldData()
   */
  public function onFieldDefinitionDelete(FieldDefinitionInterface $field_definition);

  /**
   * Purges a batch of field data.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The deleted field whose data is being purged.
   * @param $batch_size
   *   The maximum number of field data records to purge before returning,
   *   relating to the count of field data records returned by
   *   \Drupal\Core\Entity\FieldableEntityStorageInterface::countFieldData().
   *
   * @return int
   *   The number of field data records that have been purged.
   */
  public function purgeFieldData(FieldDefinitionInterface $field_definition, $batch_size);

  /**
   * Performs final cleanup after all data of a field has been purged.
   *
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage_definition
   *   The field being purged.
   */
  public function finalizePurge(FieldStorageDefinitionInterface $storage_definition);

}
