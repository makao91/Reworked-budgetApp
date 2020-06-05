<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Settings;



/**
 * Home controller
 *
 * PHP version 7.0
 */
class Edit extends Authenticated
{
    private $data;

    public function showAction()
     {
       $this->data = new Settings();
       $this->data->getIncomesName();
       $this->data->getExpensesName();
       $this->data->getPaymentMethods();


      View::renderTemplate('Edit/index.html', [
        'editNames' => $this->data,
      ]);
    }

}
