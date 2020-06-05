<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Menu extends Authenticated
{
    public function indexAction()
     {
       $fromDate = date("Y-m-01");
       $dateTo = date("Y-m-t");
      View::renderTemplate('Menu/index.html', [
        'fromDate' => $fromDate,
        'dateTo' => $dateTo
      ]);
    }
}
