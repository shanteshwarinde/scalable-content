<?php

namespace Drupal\scalable_content;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Access control handler for External Content entities.
 */
class ExternalContentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(
    EntityInterface $entity,
    $operation,
    AccountInterface $account
  ): \Drupal\Core\Access\AccessResultInterface {

    switch ($operation) {
      case 'view':
        return \Drupal\Core\Access\AccessResult::allowedIfHasPermission(
          $account,
          'view external content'
        )->cachePerPermissions();

      case 'update':
        return \Drupal\Core\Access\AccessResult::allowedIfHasPermission(
          $account,
          'edit external content'
        )->cachePerPermissions();

      case 'delete':
        return \Drupal\Core\Access\AccessResult::allowedIfHasPermission(
          $account,
          'delete external content'
        )->cachePerPermissions();
    }

    return parent::checkAccess($entity, $operation, $account);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(
    AccountInterface $account,
    array $context,
    $entity_bundle = NULL
  ): \Drupal\Core\Access\AccessResultInterface {

    return \Drupal\Core\Access\AccessResult::allowedIfHasPermission(
      $account,
      'create external content'
    )->cachePerPermissions();
  }

}
