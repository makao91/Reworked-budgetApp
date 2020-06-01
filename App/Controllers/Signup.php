<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

class Signup extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */


    public function newAction()
    {
      View::renderTemplate('Signup/new.html');
    }

    public function createAction()
    {
      $user = new User($_POST);

      if($user->save())
      {
        $user->sendActivationEmail();
        $this->redirect('/signup/success');
      }
      else{
        View::renderTemplate('Signup/new.html', ['user' =>$user]);
      }
    }

    public function successAction()
    {
        Flash::addMessage('Rejestracja wykonana pomyślnie. Sprawdź swoję pocztę w celu aktywacji konta, by móc sie zalogować', Flash::WARNING);
        View::renderTemplate('Home/index.html');
    }

    public function activateAction()
    {
      User::activate($this->route_params['token']);
      $this->redirect('/signup/activated');
    }

    public function activatedAction()
    {
      Flash::addMessage('Rejestracja zakończona sukcesem. Można się zalogować.', Flash::SUCCESS);
      View::renderTemplate('Home/index.html');

    }
}
