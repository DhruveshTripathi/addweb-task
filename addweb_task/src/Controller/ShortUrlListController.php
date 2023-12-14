<?php

namespace Drupal\addweb_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShortUrlListController extends ControllerBase {

  protected $database;

  /**
   * {@inheritdoc}
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Displays the Short URL list.
   */
  public function content() {
    // Fetch the list from the custom database table.
    $shortUrls = $this->getShortUrls();

    // Build a render array for the table.
    $build = [
      '#type' => 'tableselect',
      '#header' => [
        'id' => $this->t('ID'),
        'source_url' => $this->t('Source URL'),
        'short_url' => $this->t('Short URL'),
        // 'visited_count' => $this->t('Visited Count'),
        'date' => $this->t('Date'),
        'operations' => $this->t('Operations'),
      ],
      '#options' => $shortUrls,
      '#empty' => $this->t('No short URLs found'),
    ];

    return $build;
  }

  /**
   * Fetches the list from the custom database table.
   *
   * @return array
   *   An array of short URLs.
   */
  protected function getShortUrls() {
    $query = $this->database->select('shortlink_data', 'c')
      ->fields('c')
      ->execute();

    $shortUrls = [];
    foreach ($query as $row) {
      // $visitedCount = $this->getVisitedCount($row->short_url);

      $shortUrls[] = [
        'id' => $row->id,
        'source_url' => $row->url,
        'short_url' => $row->shortlink,
        // 'visited_count' => $visitedCount,
        'date' => $row->created,
        'operations' => [
          'data' => [
            '#type' => 'link',
            '#title' => $this->t('View details'),
            '#url' => Url::fromRoute('addweb_task.short_link_info', ['shortcode' => $row->shortlink]),
          ],
        ],
      ];
    }

    return $shortUrls;
  }

}
