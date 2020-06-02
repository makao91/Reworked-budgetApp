<?php

namespace App\Models;

use App\Auth;


use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Income extends \Core\Model
{

  public $errors = [];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };

  }


  public static function selectPlugin($selectSearchTerm)
  {
    $user = Auth::getUser();
    if(!isset($selectSearchTerm))
      {
        $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = '$user->id 'ORDER BY name";
        $fetchData = static::getUsersSelect($sql);
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
}
