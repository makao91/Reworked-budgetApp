<?php

namespace App\Models;

use App\Auth;
use PDO;

class Income extends \Core\Model
{
  public $errors = [];
  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }

  public static function selectPlugin($selectSearchTerm = false)
  {

    if(!isset($selectSearchTerm))
      {
        $fetchData = static::getCategorryName();
      }
    else
      {
        $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
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
        $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                VALUES (:user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        $stmt->bindValue(':income_category_assigned_to_user_id', $this->incomeCategory, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->incomeAmount, PDO::PARAM_INT);
        $stmt->bindValue(':date_of_income', $this->incomeDate, PDO::PARAM_STR);
        $stmt->bindValue(':income_comment', $this->incomeComment, PDO::PARAM_STR);

        return $stmt->execute();
    }
    return false;
  }


  public function validation()
  {
    if($this->incomeAmount == 0){
      $this->errors[] = 'Kwota jest wymagana.';
    }
    if($this->incomeDate == 0){
      $this->errors[] = 'Data jest wymagana.';
    }
    if($this->incomeCategory == 1){
      $this->errors[] = 'Kategoria jest wymagana.';
    }
  }

  public static function getCategorryName()
  {
    $user = Auth::getUser();
    $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = '$user->id 'ORDER BY name";
    return  $fetchData = static::getUsersSelect($sql);
  }
}
