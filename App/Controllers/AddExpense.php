<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\Expense;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Addexpense extends Authenticated
{

  public function showAction()
    {
      View::renderTemplate('Addexpense/show.html');
    }

  public function selectExpenseAction()
  {
    $searchTerm = $_POST['searchTerm'] ?? '';
    Expense::selectExpensePlugin($searchTerm);
  }
  public function selectPaymentMethodAction()
  {
    $searchTerm = $_POST['searchTerm'] ?? '';
    Expense::selectPaymentMethodPlugin($searchTerm);
  }


  public function createAction()
  {
    $expense = new Expense($_POST);

    if($expense->save())
    {
      echo true;
    }
    else{
      echo false;
    }
  }


  public function validLimitAction ()
  {
    $ooo = false;
    header('Content-Type: application/json');

    echo json_encode($ooo);

  }


}
