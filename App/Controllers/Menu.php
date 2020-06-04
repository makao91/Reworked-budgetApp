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
      View::renderTemplate('Menu/index.html');
    }
}
