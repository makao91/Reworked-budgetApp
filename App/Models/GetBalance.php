<?php

namespace App\Models;

use App\Auth;
use PDO;

class GetBalance extends \Core\Model
{
  public $incomeName = [];
  public $incomeAmount = [];
  public $incomeSaldo = 0;
  public $expenseName = [];
  public $expenseAmount = [];
  public $expenseLimit = [];
  public $expenseSaldo = 0;

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }


  public function getIncomes()
  {
    $user = Auth::getUser();
    $rawData = static::fetchFromDatabaseIncomes($user->id, $this->fromDate, $this->dateTo);

    $data = array();
    foreach ($rawData as $row) {
      $categoryIdIncome = $row["income_category_assigned_to_user_id"];
      $incomeCategoryName = static::getCategoryNameIncome($categoryIdIncome);
      foreach ($incomeCategoryName as $row2) {
        $this->incomeName[] = $row2['name'];
        $this->incomeAmount[] = $row["SUM(amount)"];
        $this->incomeSaldo += $row["SUM(amount)"];
      };
    };
  }

  static public function getCategoryNameIncome($categoryIdIncome)
  {
    $db = static::getDB();
    $sql = "SELECT name FROM incomes_category_assigned_to_users WHERE id = :categoryIdIncome";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':categoryIdIncome', $categoryIdIncome, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  static public function fetchFromDatabaseIncomes($userId, $dateFrom, $dateTo)
  {
    $db = static::getDB();

    $sql = 'SELECT income_category_assigned_to_user_id, SUM(amount) FROM incomes WHERE user_id = :userId AND date_of_income >= :dateFrom AND date_of_income <= :dateTo GROUP BY income_category_assigned_to_user_id';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
    $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function getExpenses()
  {
    $user = Auth::getUser();
    $rawData = static::fetchFromDatabaseExpenses($user->id, $this->fromDate, $this->dateTo);

    $data = array();
    foreach ($rawData as $row) {
      $categoryIdExpense = $row["expense_category_assigned_to_user_id"];
      $expenseCategoryName = static::getCategoryNameExpense($categoryIdExpense);
      foreach ($expenseCategoryName as $row2) {
        $this->expenseName[] = $row2['name'];
        $expLimit = $row2['paymentlimit'];
        $this->expenseAmount[] = $row["SUM(amount)"];
        $this->expenseSaldo += $row["SUM(amount)"];
        if($expLimit == "-"){
          $expLimit = null;
        }
        if($expLimit && $expLimit >= $row["SUM(amount)"]){
            $this->expenseLimit[] ='Limit: <span style="color:green;">'.$expLimit.' zł'.'</span>';
        }else if($expLimit && $expLimit < $row["SUM(amount)"]){
          $this->expenseLimit[] ='Limit: <span style="color:red;">!'.$expLimit.' zł'.'!</span>';
        }else if ($expLimit == null){
          $this->expenseLimit[] = '-';
        }
      };
    };
  }



  static public function getCategoryNameExpense($categoryIdExpense)
  {
    $db = static::getDB();
    $sql = "SELECT name, paymentlimit FROM expenses_category_assigned_to_users WHERE id = :categoryIdExpense";
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
