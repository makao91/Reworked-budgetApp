<?php

namespace App\Models;

use App\Auth;
use PDO;

class Expense extends \Core\Model
{

  public $errors = [];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }

  public static function selectExpensePlugin($selectSearchTerm)
  {
    $user = Auth::getUser();
    if(!isset($selectSearchTerm))
      {
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = '$user->id 'ORDER BY name";
        $fetchData = static::getUsersSelect($sql);
      }
    else
      {
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
        $fetchData = static::getUsersSelect($sql);
      }

    $data = array();
    foreach ($fetchData as $row) {
      $data[] = array('id'=>$row['id'], 'text'=>$row['name']);
    };
    echo json_encode($data);
  }


  public static function selectPaymentMethodPlugin($selectSearchTerm)
  {
    $user = Auth::getUser();
    if(!isset($selectSearchTerm))
      {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = '$user->id 'ORDER BY name";
        $fetchData = static::getUsersSelect($sql);
      }
    else
      {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
        $fetchData = static::getUsersSelect($sql);
      }

    $data = array();
    foreach ($fetchData as $row) {
      $data[] = array('id'=>$row['id'], 'text'=>$row['name']);
    };
    echo json_encode($data);
  }


  static public function getUsersSelect($sql)
  {
    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function save()
  {
    $user = Auth::getUser();
    $this->validation();

    if(empty($this->errors))
    {
        $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        $stmt->bindValue(':expense_category_assigned_to_user_id', $this->expenseCategory, PDO::PARAM_INT);
        $stmt->bindValue(':payment_method_assigned_to_user_id', $this->paymentMethod, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->expenseAmount, PDO::PARAM_INT);
        $stmt->bindValue(':date_of_expense', $this->expenseDate, PDO::PARAM_STR);
        $stmt->bindValue(':expense_comment', $this->expenseComment, PDO::PARAM_STR);

        return $stmt->execute();
    }
    return false;
  }


  public function validation()
  {
    if($this->paymentMethod == 1){
      $this->errors[] = 'Metoda płatności jest wymagana.';
    }
    if($this->expenseAmount == 0){
      $this->errors[] = 'Kwota jest wymagana.';
    }
    if($this->expenseDate == 0){
      $this->errors[] = 'Data jest wymagana.';
    }
    if($this->expenseCategory == 1){
      $this->errors[] = 'Kategoria jest wymagana.';
    }
  }
}
