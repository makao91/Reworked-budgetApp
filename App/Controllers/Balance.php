<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\GetBalance;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Balance extends Authenticated
{

  public function showAction()
   {
      View::renderTemplate('Balance/show.html');
   }

   public function getBalanceAction()
   {
     $balance = new GetBalance($_POST);

     $balance->getIncomes();
     var_dump($balance);
     View::renderTemplate('Balance/show.html', [
       'income' => $balance,
     ]);

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
