<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Expense;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class AddExpense extends Authenticated
{

  protected function before()
  {
    parent::before();
    $this->user = Auth::getUser();
  }

    public function showAction()
     {
      View::renderTemplate('AddExpense/show.html');
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
      Flash::addMessage('Wydatek dodano pomyślnie');
      $this->showAction();
    }
    else{
      View::renderTemplate('AddExpense/show.html', ['expense' =>$expense]);
    }
  }
}

  /*  public function editAction()
    {
      View::renderTemplate('Profile/edit.html',[
        'user' => $this->user
      ]);

    }

    public function updateAction()
    {

      if($this->user->updateProfile($_POST))
      {
        Flash::addMessage('Changes saved');
        $this->redirect('/profile/show');
      } else {
        View::renderTemplate('Profile/edit.html', [
          'user' => $this->user
        ]);
      }
    }
    */
