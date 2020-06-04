<?php

namespace App\Models;

use App\Auth;
use PDO;

class IncomeChart extends \Core\Model
{
  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }


  public function getIncomesData()
  {
    $user = Auth::getUser();
    $rawData = static::fetchFromDatabaseIncomes($user->id, $this->dateFrom, $this->dateTo);
    $data = array();

    foreach ($rawData as $row) {
      $categoryIdIncome = $row["income_category_assigned_to_user_id"];
      $incomeCategoryName = static::getCategoryNameIncome($categoryIdIncome);
      foreach ($incomeCategoryName as $row2) {
        $data[] = array('name'=>$row2['name'], 'amount'=>$row['SUM(amount)']);
      };
    };
    return $data;
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
}
