<?php

/**
 * @file
 * Contains \Drupal\crime_logs\Controller\CrimeLogsController.
 */

namespace Drupal\crime_logs\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CrimeLogsController.
 *
 * @package Drupal\crime_logs\Controller
 */
class CrimeLogsController extends ControllerBase {

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
      '#markup' => '<iframe src="http://www.police.gatech.edu/crimelog.php" height="100%" width="100%" style="width:100%; height:1000px; border:none;"></iframe>',
      '#cache' => [
          'max-age' => 0,
      ],
    ];
  }
  
}
