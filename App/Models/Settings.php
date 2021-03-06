<?php

namespace App\Models;

use App\Auth;
use PDO;

class Settings extends \Core\Model
{
  public $incomeName = [];
  public $expenseNameWithLimit = [];
  public $expenseName = [];
  public $expenseLimit = [];
  public $paymentMethod = [];



  public function getIncomesName()
  {
    $user = Auth::getUser();
    $rawData = static::getCategoryNameIncome($user->id);

    foreach ($rawData as $row) {
        $this->incomeName[] = $row['name'];
    };
  }

  public function getExpensesNameWithLimit()
  {
    $user = Auth::getUser();
    $rawData = static::getCategoryNameExpense($user->id);

    foreach ($rawData as $row) {
      $this->expenseName[] = $row['name'];
      $this->expenseLimit[] = $row['paymentlimit'];
        if($row['paymentlimit'] != "-"){
          $this->expenseNameWithLimit[] = $row['name'].'<span class="limit"> Limit: '. $row['paymentlimit'].' zł</span>';
        }else{
          $this->expenseNameWithLimit[] = $row['name'];
        }
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
    $sql = "SELECT name, paymentlimit FROM expenses_category_assigned_to_users WHERE user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  static public function getCategoryIdExpense($name)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name LIKE '%".$name."%'";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  static public function getCategoryIdIncome($name)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :name";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);

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


  public function deleteCatIn($categoryName)
  {
    $user = Auth::getUser();
    static::updateIncomes($categoryName);


    $db = static::getDB();
    $sql = "DELETE FROM incomes_category_assigned_to_users WHERE name = :name AND user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
  }
  public function deleteCatPay($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "DELETE FROM payment_methods_assigned_to_users WHERE name = :name AND user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
  }




  public function deleteCatEx($categoryName)
  {
    $user = Auth::getUser();
    static::updateExpenses($categoryName);


    $db = static::getDB();
    $sql = "DELETE FROM expenses_category_assigned_to_users WHERE name LIKE '%".$categoryName."%' AND user_id = :user_id ";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);

    $stmt->execute();
  }
public static function updateExpenses($categoryName)
{
  $categoryId = "";
  $categoryInneId = "";
  $rawData = static::getCategoryIdExpense($categoryName);
  $rawData2 = static::getCategoryIdExpense("Inne");
  foreach ($rawData as $row) {
      $categoryId = $row['id'];
  };
  foreach ($rawData2 as $row) {
      $categoryInneId = $row['id'];
  };
  $db = static::getDB();
  $sql = "UPDATE expenses SET expense_category_assigned_to_user_id = :catInne WHERE expense_category_assigned_to_user_id = :catName";
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':catInne', $categoryInneId, PDO::PARAM_STR);
  $stmt->bindValue(':catName', $categoryId, PDO::PARAM_STR);

  $stmt->execute();
}
public static function updateIncomes($categoryName)
{
  $rawData = static::getCategoryIdIncome($categoryName);
  $rawData2 = static::getCategoryIdIncome("Inne");
  foreach ($rawData as $row) {
      $categoryId = $row['id'];
  };
  foreach ($rawData2 as $row) {
      $categoryInneId = $row['id'];
  };
  $db = static::getDB();
  $sql = "UPDATE incomes SET income_category_assigned_to_user_id = :catInne WHERE income_category_assigned_to_user_id = :catName";
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':catInne', $categoryInneId, PDO::PARAM_STR);
  $stmt->bindValue(':catName', $categoryId, PDO::PARAM_STR);

  $stmt->execute();
}




  public function addCatIn($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "INSERT INTO incomes_category_assigned_to_users(user_id, name) VALUES (:user_id, :name)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
  }
  public function addCatPay($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "INSERT INTO payment_methods_assigned_to_users(user_id, name) VALUES (:user_id, :name)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
  }
  public function addCatEx($categoryData)
  {
    $categoryName = $categoryData['catName'];
    $categoryLimit = $categoryData['catLimit'];

    if($categoryLimit == 0){
      $categoryLimit = null;
    }

    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "INSERT INTO expenses_category_assigned_to_users(user_id, name, paymentlimit) VALUES (:user_id, :name, :paymenylimit)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':paymenylimit', $categoryLimit, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
  }

  public function getLi($catName)
  {
    $name = $catName['shortname'];
    $user = Auth::getUser();
    $limitData = static::getLimitFromDB($name);

    foreach ($limitData as $row) {
        echo $row['paymentlimit'];
    };
  }

  public static function getLimitFromDB($categoryName)
  {
    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "SELECT paymentlimit FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $categoryName, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function editCatIn($editData)
  {
    $newCatName = $editData['catName'];
    $oldCatName = $editData['oldCatName'];

    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "UPDATE incomes_category_assigned_to_users SET name = :newName WHERE user_id = :user_id AND name = :oldName";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':newName', $newCatName, PDO::PARAM_STR);
    $stmt->bindValue(':oldName', $oldCatName, PDO::PARAM_STR);

    $stmt->execute();
  }
  public function editCatPay($editData)
  {
    $newCatName = $editData['catName'];
    $oldCatName = $editData['oldCatName'];

    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "UPDATE payment_methods_assigned_to_users SET name = :newName WHERE user_id = :user_id AND name = :oldName";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':newName', $newCatName, PDO::PARAM_STR);
    $stmt->bindValue(':oldName', $oldCatName, PDO::PARAM_STR);

    $stmt->execute();
  }
  public function editPassword($editData)
  {
    $newPassword = $editData['newPass'];
    $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);

    $user = Auth::getUser();
    $db = static::getDB();
    $sql = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

    $stmt->execute();
  }


  public function editName($editData)
  {
    $newName = $editData['newName'];
      $user = Auth::getUser();
      $db = static::getDB();
      $sql = "UPDATE users SET name = :name WHERE id = :id";
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
      $stmt->bindValue(':name', $newName, PDO::PARAM_STR);

      $stmt->execute();
  }


  public function editEmail($editData)
  {
    $newEmail = $editData['newEmail'];

        $user = Auth::getUser();
        $db = static::getDB();
        $sql = "UPDATE users SET email = :email WHERE id = :id";
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
        $stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);

        $stmt->execute();
  }


  public function editCatEx($editData)
  {
    $newCatName = $editData['catName'];
    $oldCatName = $editData['oldCatName'];
    $limitPay = $editData['limit'];
    if($limitPay == 0){
      $limitPay = null;
    }

    $user = Auth::getUser();
    $db = static::getDB();

      $sql = "UPDATE expenses_category_assigned_to_users SET name = :newName, paymentlimit = :limitPay WHERE user_id = :user_id AND name = :oldName";
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $stmt->bindValue(':limitPay', $limitPay, PDO::PARAM_INT);
      $stmt->bindValue(':newName', $newCatName, PDO::PARAM_STR);
      $stmt->bindValue(':oldName', $oldCatName, PDO::PARAM_STR);

    $stmt->execute();
  }


}
