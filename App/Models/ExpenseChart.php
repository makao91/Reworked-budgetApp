<?php

namespace App\Models;

use App\Auth;
use PDO;

class ExpenseChart extends \Core\Model
{

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }

  public function getExpensesData()
  {
    $user = Auth::getUser();
    $rawData = static::fetchFromDatabaseExpenses($user->id, $this->dateFrom, $this->dateTo);
    $data = array();

    foreach ($rawData as $row) {
      $categoryIdExpense = $row["expense_category_assigned_to_user_id"];
      $expenseCategoryName = static::getCategoryNameExpense($categoryIdExpense);
      foreach ($expenseCategoryName as $row2) {
        $data[] = array('name'=>$row2['name'], 'amount'=>$row['SUM(amount)']);
      };
    };
    return $data;
  }

  static public function getCategoryNameExpense($categoryIdExpense)
  {
    $db = static::getDB();
    $sql = "SELECT name FROM expenses_category_assigned_to_users WHERE id = :categoryIdExpense";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':categoryIdExpense', $categoryIdExpense, PDO::PARAM_STR);
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  static public function fetchFromDatabaseExpenses($userId, $dateFrom, $dateTo)
  {
    $db = static::getDB();

    $sql = 'SELECT expense_category_assigned_to_user_id, SUM(amount) FROM expenses WHERE user_id = :userId AND date_of_expense >= :dateFrom AND date_of_expense <= :dateTo GROUP BY expense_category_assigned_to_user_id';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
    $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
