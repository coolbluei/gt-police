<?php

/**
 * @file
 * Contains \Drupal\crime_logs\Controller\NonCrimeLogsController.
 */

namespace Drupal\crime_logs\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NonCrimeLogsController.
 *
 * @package Drupal\crime_logs\Controller
 */
class NonCrimeLogsController extends ControllerBase {

  /**
    * Content.
    *
    * @return string
    *   Return page content.
    */
  public function content() {
    return [
        '#type' => 'inline_template',
        '#template' => '<iframe src="https://www.police.gatech.edu/noncrimelog.php" height="100%" width="100%" style="width:100%; height:1000px; border:none;"></iframe>',
        '#cache' => [
            'max-age' => 0,
        ],
      ];
    }
  
}
