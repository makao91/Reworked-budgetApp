<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Items extends Authenticated
{

    public function indexAction()
     {
      View::renderTemplate('Items/index.html');
    }
}
