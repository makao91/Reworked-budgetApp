<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{


    public function createAction()
    {
      $user = User::authenticate($_POST['email'], $_POST['password']);

      $remember_me = isset($_POST['remember_me']);

      if($user){

        Auth::login($user, $remember_me);

        $this->redirect(Auth::getReturnToPage());

      } else{
        Flash::addMessage('Nie udało się zalogować. Spróbuj jeszcze raz.', Flash::WARNING);
        View::renderTemplate('Home/index.html', [
          'email' => $_POST['email'],
          'remember_me' => $remember_me]);
      }
    }


    public function destroyAction()
    {
      Auth::logout();
      $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction()
    {
      Flash::addMessage('Wylogowano.');
      $this->redirect('/');
    }

}
