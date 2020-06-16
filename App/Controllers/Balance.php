<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\GetBalance;
use \App\Models\IncomeChart;
use \App\Models\ExpenseChart;

class Balance extends Authenticated
{
  private $data;

  public function showAction()
   {  
      View::renderTemplate('Balance/show.html', [
        'firstDay' => $firstDay,
        'lastDay' => $lastDay
      ]);
      $firstDay = null;
      $lastDay = null;
   }

   public function getBalanceAction()
   {
     $this->data = new GetBalance($_POST);
     $this->data->getIncomes();
     $this->data->getExpenses();
     View::renderTemplate('Balance/show.html', [
       'balance' => $this->data
     ]);
   }
   public function ajaxIncomeAction()
   {
     $json = new IncomeChart($_POST);
     $var = $json->getIncomesData();
     echo json_encode($var);
   }
   public function ajaxExpenseAction()
   {
     $json = new ExpenseChart($_POST);
     $var = $json->getExpensesData();
     echo json_encode($var);
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
