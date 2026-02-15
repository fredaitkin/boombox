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
    return [
      'description' => [
        '#markup' => '<p>Artist listing page.</p>',
      ],
      'add_link' => Link::fromTextAndUrl($this->t('Add Artist'), Url::fromRoute('boombox_artist.add'))->toRenderable(),
      'artist_view' => [
        '#type' => 'view',
        '#name' => 'artists',
        '#display_id' => 'block_1',
        '#arguments' => [],
      ],
    ];
  }

}
