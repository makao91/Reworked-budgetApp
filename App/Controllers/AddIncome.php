<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Income;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class AddIncome extends Authenticated
{

  protected function before()
  {
    parent::before();
    $this->user = Auth::getUser();
  }

    public function showAction()
     {
      View::renderTemplate('AddIncome/show.html', [
        'user' => $this->user
      ]);
    }

    public function createAction()
    {
      $income = new Income($_POST);

      if($income->save())
      {
        $user->sendActivationEmail();
        $this->redirect('/signup/success');
      }
      else{
        View::renderTemplate('Signup/new.html', ['user' =>$user]);
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
}
