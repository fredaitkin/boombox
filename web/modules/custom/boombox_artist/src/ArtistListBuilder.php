<?php

namespace Drupal\boombox_artist;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArtistListBuilder extends EntityListBuilder {

  protected DateFormatterInterface $dateFormatter;

  protected string $displayTimezone;

  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter, ConfigFactoryInterface $config_factory) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
    $timezone = $config_factory->get('system.date')->get('timezone.default');
    $this->displayTimezone = $timezone ?: date_default_timezone_get();
  }

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type): static {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter'),
      $container->get('config.factory')
    );
  }

  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['created'] = $this->t('Created');

    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity): array {
    $row['id'] = $entity->id();
    $row['name'] = $entity->label();
    $created = $entity->get('created')->value;
    $row['created'] = $created ? $this->dateFormatter->format((int) $created, 'short', '', $this->displayTimezone) : $this->t('N/A');

    return $row + parent::buildRow($entity);
  }

}
