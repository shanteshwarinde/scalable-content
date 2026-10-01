<?php

namespace Drupal\scalable_content\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Psr\Log\LoggerInterface;

/**
 * Handles External Content entity events.
 */
class ExternalContentEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructs the event subscriber.
   */
  public function __construct(
    protected LoggerInterface $logger,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      'scalable_content.entity_insert' => 'onEntityInsert',
      'scalable_content.entity_update' => 'onEntityUpdate',
    ];
  }

  /**
   * Handles entity insert events.
   */
  public function onEntityInsert(GenericEvent $event): void {
    $entity = $event->getSubject();

    if ($entity->getEntityTypeId() === 'external_content') {
      $this->logger->notice(
        'External Content entity inserted: @id',
        ['@id' => $entity->id()]
      );
    }
  }

  /**
   * Handles entity update events.
   */
  public function onEntityUpdate(GenericEvent $event): void {
    $entity = $event->getSubject();

    if ($entity->getEntityTypeId() === 'external_content') {
      $this->logger->notice(
        'External Content entity updated: @id',
        ['@id' => $entity->id()]
      );
    }
  }

}
