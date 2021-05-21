<?php

/**
 * @file
 * Contains \Drupal\crime_logs\Controller\CsaController.
 */

namespace Drupal\crime_logs\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CsaController.
 *
 * @package Drupal\crime_logs\Controller
 */
class CsaController extends ControllerBase {

  /**
    * Content.
    *
    * @return string
    *   Return page content.
    */
  public function content() {
    $content = $this->getContent();

    return [
      '#type' => 'markup',
      '#markup' => $content,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  private function getContent() {
    $urlprefix = "http://reports.police.gatech.edu/public/viewcsa.asp";

    return readfile($urlprefix);
  }
  
}
