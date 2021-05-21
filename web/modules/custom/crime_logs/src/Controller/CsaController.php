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
    return [
        '#type' => 'inline_template',
        '#template' => '<iframe src="https://www.police.gatech.edu/csa.php" height="100%" width="100%" style="width:100%; height:1000px; border:none;"></iframe>',
        '#cache' => [
            'max-age' => 0,
        ],
      ];
    }
  
}
