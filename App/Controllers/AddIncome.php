<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\Income;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Addincome extends \Core\Controller
{

    public function showAction()
     {
      View::renderTemplate('Addincome/show.html');
    }

  public function selectAction()
  {
    $searchTerm = $_POST['searchTerm'] ?? '';
    Income::selectPlugin($searchTerm);
  }

  public function createAction()
  {
    $income = new Income($_POST);
    if($income->save())
    {
      Flash::addMessage('Przychód dodano pomyślnie');
      $this->showAction();
    }
    else{
      View::renderTemplate('AddIncome/show.html', ['income' =>$income]);
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
