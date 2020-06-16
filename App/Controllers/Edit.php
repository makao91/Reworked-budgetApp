<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Settings;
use App\Auth;



/**
 * Home controller
 *
 * PHP version 7.0
 */
class Edit extends Authenticated
{
    private $data;
    private $user;

    public function showAction()
     {
       $this->data = new Settings();
       $this->data->getIncomesName();
       $this->data->getExpensesNameWithLimit();
       $this->data->getPaymentMethods();
       $user = Auth::getUser();
      View::renderTemplate('Edit/index.html', [
        'editNames' => $this->data,
        'user' => $user,
      ]);
    }


    public function deleteCategoryIncome()
    {
      $this->data = new Settings();
      $this->data->deleteCatIn($_POST['catName']);
    }

    
    public function deleteCategoryPayment()
    {
      $this->data = new Settings();
      $this->data->deleteCatPay($_POST['catName']);
    }


    public function deleteCategoryExpense()
    {
      $this->data = new Settings();
      $this->data->deleteCatEx($_POST['catName']);
    }



    public function addCategoryIncome()
    {
      $this->data = new Settings();
      $this->data->addCatIn($_POST['catName']);
    }
    public function addCategoryPayment()
    {
      $this->data = new Settings();
      $this->data->addCatPay($_POST['catName']);
    }
    public function addCategoryExpense()
    {
      $this->data = new Settings();
      $this->data->addCatEx($_POST);
    }

    public function editCategoryIncome()
    {
      $this->data = new Settings();
      $this->data->editCatIn($_POST);
    }
    public function editCategoryPayment()
    {
      $this->data = new Settings();
      $this->data->editCatPay($_POST);
    }
    public function editCategoryExpense()
    {
      $this->data = new Settings();
      $this->data->editCatEx($_POST);
    }
    public function getLimit()
    {
      $this->data = new Settings();
       echo $this->data->getLi($_POST);
    }
    public function passwordAction()
    {
      $this->data = new Settings();
       $this->data->editPassword($_POST);
    }
    public function nameAction()
    {
      $this->data = new Settings();
       $this->data->editName($_POST);
    }
    public function emailAction()
    {
      $this->data = new Settings();
       $this->data->editEmail($_POST);
    }

}
