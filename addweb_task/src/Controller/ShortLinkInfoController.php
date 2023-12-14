<?php

namespace Drupal\addweb_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShortLinkInfoController extends ControllerBase {

  /**
   * Displays information about the shortened URL.
   *
   * @param string $shortcode
   *   The short URL identifier.
   *
   * @return array
   *   The render array.
   */
  public function content($shortcode) {
    $fullUrl = $this->getFullUrl($shortcode);
    if ($fullUrl) {
      return new RedirectResponse($fullUrl);
    }

    // Customize this render array to display information about the short URL.
    $build = [
      '#markup' => $this->t('Short URL info for shortcode: @shortcode', ['@shortcode' => $shortcode]),
    ];

    return $build;
  }

  /**
   * Fetches the full URL from the main database table.
   *
   * @param string $shortcode
   *   The short URL identifier.
   *
   * @return string|null
   *   The full URL or null if not found.
   */
  protected function getFullUrl($shortcode) {
     $fullUrl = $this->database->select('shortlink_data', 'c')
       ->fields('c', ['url'])
       ->condition('shortlink', $shortcode)
       ->execute()
       ->fetchField();

    return $fullUrl;
  }

}
