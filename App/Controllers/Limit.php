<?php

namespace App\Controllers;

use \App\Models\Expense;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Limit extends \Core\Controller
{
  public function validateLimitAction()
    {
      $expense = new Expense($_POST);

      $is_exceeded = $expense->checkLimit();
      header('Content-Type: application/json');
      echo json_encode($is_exceeded);
    }
}
