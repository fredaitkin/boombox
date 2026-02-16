<?php

namespace Drupal\boombox_artist\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Artist content entity.
 *
 * @ContentEntityType(
 *   id = "boombox_artist",
 *   label = @Translation("Artist"),
 *   label_collection = @Translation("Artists"),
 *   base_table = "boombox_artist",
 *   admin_permission = "access content",
 *   handlers = {
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "list_builder" = "Drupal\boombox_artist\ArtistListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\boombox_artist\Form\ArtistForm",
 *       "edit" = "Drupal\boombox_artist\Form\ArtistForm",
 *       "delete" = "Drupal\boombox_artist\Form\ArtistDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *   },
 *   links = {
 *     "canonical" = "/artists/{boombox_artist}",
 *     "add-form" = "/artists/add",
 *     "edit-form" = "/artists/{boombox_artist}/edit",
 *     "delete-form" = "/artists/{boombox_artist}/delete",
 *     "collection" = "/artists/manage"
 *   }
 * )
 */
class Artist extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The artist ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The artist name.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
        'settings' => [
          'size' => 60,
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));

    return $fields;
  }

}
