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
      '#markup' => $content,
      '#cache' => [
          'max-age' => 0,
      ],
    ];
  }

  private function getContent() {
    $urlprefix = "http://reports.police.gatech.edu/public/crimelog.asp";

    //sanitize user input
    if ($_GET['offset'] == strval(intval($_GET['offset'])))
        {
            $offsetsuffix = $_GET['offset'];
        }
    
    if ($_GET['OCA'] == strval(intval($_GET['OCA'])))
        {
            $OCAsuffix = $_GET['OCA'];
        }
    
    // OCA can not be negative, offset = -1 is used to go to last page, any other negative numbers are not used.
    if ($offsetsuffix < -1)
        {
            $offsetsuffix = 0;
        }
    
    if ($OCAsuffix < 0)
        {
            $OCAsuffix = 0;
        }
    
    //Create url values to be passed to reports.police.gatech.edu if requested, skipped if values were not passed.
    
    if (isset($offsetsuffix))
        {
            $offsetsuffix = "offset=".$offsetsuffix;
        }
    
    
    if (isset($OCAsuffix))
        {
            $OCAsuffix = "OCA=".$OCAsuffix;
        }
    
    //create final url. This adds "?" and "&" if needed
    $url = $urlprefix;
    
    if ((isset($OCAsuffix)) || (isset($offsetsuffix)))
        {
            $url = $urlprefix."?".$OCAsuffix.$offsetsuffix;
        }
    
    if ((isset($OCAsuffix)) && (isset($offsetsuffix)))
        {
            $url = $urlprefix."?".$OCAsuffix."&".$offsetsuffix;
        }
    
    return readfile($url);
        
  }
  
}
