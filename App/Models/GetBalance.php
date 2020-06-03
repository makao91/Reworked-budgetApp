<?php

namespace App\Models;

use App\Auth;


use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class GetBalance extends \Core\Model
{

  public $incomeName = [];
  public $incomeAmount = [];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };


  }


  public function getIncomes()
  {
    $dateFrom = $this->fromDate;
    $dateTo = $this->dateTo;
    $user = Auth::getUser();
    $summIncome = 0;

    $rawData = static::fetchFromDatabaseIncomes($user->id, $dateFrom, $dateTo);

      $data = array();
    foreach ($rawData as $row) {
      $summIncome+=$row["SUM(amount)"];
      $categoryIdIncome = $row["income_category_assigned_to_user_id"];
      $incomeCategoryName = static::getCategoryNameIncome($categoryIdIncome);
      foreach ($incomeCategoryName as $row2) {
        $this->incomeName[] = $row2['name'];
        $this->incomeAmount[] = $row["SUM(amount)"];
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
}
