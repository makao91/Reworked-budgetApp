<?php

namespace App\Models;

use App\Auth;
use PDO;

class Settings extends \Core\Model
{
  public $incomeName = [];
  public $expenseName = [];
  public $paymentMethod = [];


  public function getIncomesName()
  {
    $user = Auth::getUser();
    $rawData = static::getCategoryNameIncome($user->id);

    foreach ($rawData as $row) {
        $this->incomeName[] = $row['name'];
    };
  }
  public function getExpensesName()
  {
    $user = Auth::getUser();
    $rawData = static::getCategoryNameExpense($user->id);

    foreach ($rawData as $row) {
        $this->expenseName[] = $row['name'];
    };
  }
  public function getPaymentMethods()
  {
    $user = Auth::getUser();
    $rawData = static::getPaymentMethodsNames($user->id);

    foreach ($rawData as $row) {
        $this->paymentMethod[] = $row['name'];
    };
  }

  static public function getCategoryNameIncome($id)
  {
    $db = static::getDB();
    $sql = "SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  static public function getCategoryNameExpense($id)
  {
    $db = static::getDB();
    $sql = "SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  static public function getPaymentMethodsNames($id)
  {
    $db = static::getDB();
    $sql = "SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function deleteCat($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "DELETE FROM incomes_category_assigned_to_users WHERE name = :name AND user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();

  }


  public function addCat($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "INSERT INTO incomes_category_assigned_to_users(user_id, name) VALUES (:user_id, :name)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();    
  }
}
