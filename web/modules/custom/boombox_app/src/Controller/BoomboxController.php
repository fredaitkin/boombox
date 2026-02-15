<?php

namespace Drupal\boombox_app\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class BoomboxController extends ControllerBase {

  public function home(): array {
    return [
      'menu' => [
        '#theme' => 'item_list',
        '#title' => $this->t('Menu'),
        '#items' => [
          Link::fromTextAndUrl($this->t('Songs'), Url::fromRoute('boombox_app.songs'))->toRenderable(),
          Link::fromTextAndUrl($this->t('Artists'), Url::fromRoute('boombox_app.artists'))->toRenderable(),
        ],
      ],
    ];
  }

  public function songs(): array {
    return [
      '#markup' => '<p>Song listing page.</p>',
    ];
  }

  public function artists(): array {
    $records = \Drupal::database()
      ->select('boombox_artist', 'a')
      ->fields('a', ['id', 'name', 'created'])
      ->orderBy('a.name', 'ASC')
      ->execute()
      ->fetchAll();

    $rows = [];
    $date_formatter = \Drupal::service('date.formatter');
    $timezone = \Drupal::config('system.date')->get('timezone.default') ?: date_default_timezone_get();
    foreach ($records as $record) {
      $rows[] = [
        'id' => (int) $record->id,
        'name' => (string) $record->name,
        'created' => $date_formatter->format((int) $record->created, 'short', '', $timezone),
      ];
    }

    return [
      'description' => [
        '#markup' => '<p>Artist listing page.</p>',
      ],
      'add_link' => Link::fromTextAndUrl($this->t('Add Artist'), Url::fromRoute('boombox_artist.add'))->toRenderable(),
      'artist_table' => [
        '#type' => 'table',
        '#header' => [
          $this->t('ID'),
          $this->t('Artist'),
          $this->t('Created'),
        ],
        '#rows' => $rows,
        '#empty' => $this->t('No artists found.'),
      ],
    ];
  }

}
