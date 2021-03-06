<?php

namespace App\Models;
use \App\Token;
use \App\Mail;
use \Core\View;
use PDO;

class User extends \Core\Model
{

  public $errors = [];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }


  public function save()
  {
    $this->validation();

    if(empty($this->errors)){

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $token = new Token();
        $hashed_token = $token->getHash();
        $this->activation_token = $token->getValue();

        $sql = 'INSERT INTO users (name, email, password_hash, activation_hash)
                VALUES (:name, :email, :password_hash, :activation_hash)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

        return $stmt->execute();
    }
    return false;
  }


  public function validation()
  {
    if($this->name == ''){
      $this->errors[] = 'Imię jest wymagane.';
    }

    if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
      $this->errors[] = 'Niepoprawny format email.';
    }
    if(static::emailExists($this->email, $this->id ?? null)){
      $this->errors[] = 'Wskazany email już istnieje w bazie. Zaloguj się, lub wprowadź inny.';
    }
    if(isset($this->password))
    {
        if(strlen($this->password) < 6){
          $this->errors[] = 'Wprowadź conajmniej 6 znaków do hasła';
        }
        if(preg_match('/.*[a-z]+.*/i', $this->password) == 0){
          $this->errors[] = 'Hasło wymaga conajmniej jednej litery.';
        }
        if(preg_match('/.*\d+.*/', $this->password) == 0){
          $this->errors[] = 'Hasło wymaga conajmniej jednej cyfry.';
        }
    }
  }


  public static function emailExists($email, $ignore_id = null)
  {
    $user = static::findByEmail($email);
      if($user){
        if($user->id != $ignore_id){
          return true;
        }
      }
      return false;
  }


  public static function findByEmail($email)
  {
    $sql = 'SELECT * From users WHERE email =:email';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    return $stmt->fetch();
  }


  public static function authenticate($email, $password)
  {
    $user = static::findByEmail($email);

    if($user && $user->is_active)
    {
      if(password_verify($password, $user->password_hash)){
        return $user;
      }
      return false;
    }
  }


  public static function findById($id)
  {
    $sql = 'SELECT * From users WHERE id =:id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    return $stmt->fetch();
  }


  public function rememberLogin()
  {
    $token = new Token();
    $hashed_token = $token->getHash();
    $this->remember_token = $token->getValue();

    $this->expiry_timestamp = time() + 60*60*24*30;  //30 days

    $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
            VALUES (:token_hash, :user_id, :expires_at)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

    return $stmt->execute();
  }

  public static function sendPasswordReset($email)
  {
    $user = static::findByEmail($email);
    if($user)
    {
        if($user->startPasswordReset())
        {
          $user->sendPasswordResetEmail();
          echo 5; //returning value to ajax in home/index
        }
    }
  }


  protected function startPasswordReset()
  {
    $token = new Token();
    $hashed_token = $token->getHash();
    $this->password_reset_token = $token->getValue();

    $expiry_timestamp = time() + 60*60*2; //2 hours

    $sql = 'UPDATE users SET password_reset_hash = :token_hash, password_reset_exp = :expires_at
            WHERE id = :id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);

    return $stmt->execute();
  }


  protected function sendPasswordResetEmail()
  {
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/password/reset/'.$this->password_reset_token;
    $text = View::getTemplate('Password/reset_email.txt', ['url'=>$url]);
    $html = View::getTemplate('Password/reset_email.html', ['url'=>$url]);

    Mail::send($this->email, 'Reset hasła', $text, $html);
  }



/*
  public static function findByActivationToken($token)
  {

    $sql = 'SELECT * FROM users WHERE activation_hash = :token_hash';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $token, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    return $user = $stmt->fetch();
  }
*/



  public static function findByPasswordReset($token)
  {
    $token = new Token($token);
    $hashed_token = $token->getHash();

    $sql = 'SELECT * FROM users WHERE password_reset_hash = :token_hash';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    $user = $stmt->fetch();

    if($user)
    {
      if(strtotime($user->password_reset_exp)>time()){
        return $user;
      }
    }
  }


  public function resetPassword($password)
  {
    $this->password = $password;

    $this->validation();
    if(empty($this->errors))
    {
      $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

      $sql = 'UPDATE users
              SET password_hash = :password_hash,
                  password_reset_hash = NULL,
                  password_reset_exp = NULL
              WHERE id = :id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

      return $stmt->execute();
    }
      return false;
  }


  public function sendActivationEmail()
  {
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/signup/activate/'.$this->activation_token;
    $text = View::getTemplate('Signup/activation_email.txt', ['url'=>$url]);
    $html = View::getTemplate('Signup/activation_email.html', ['url'=>$url]);

    Mail::send($this->email, 'Account activation', $text, $html);
  }


  public static function activate($value)
  {
    $token = new Token($value);
    $hashed_token = $token->getHash();

    $sql = 'UPDATE users
            SET is_active = 1,
                activation_hash = null
            WHERE activation_hash = :activation_hash';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

    $stmt->execute();
  }

public function addDefaultCategoriesIncomes()
{
  $user = static::findByEmail($this->email);
  $result = static::getDefaultCategoriesIncomes();
  foreach ($result as $row) {
    $categoryName = $row['name'];
    $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES (:user_id, :categoryName)';

    static::executeDefaultCategories($sql, $categoryName, $user->id);
  };
}

public function addDefaultCategoriesExpenses()
{
  $user = static::findByEmail($this->email);
  $result = static::getDefaultCategoriesExpenses();
  foreach ($result as $row) {
    $categoryName = $row['name'];
    $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (:user_id, :categoryName)';

    static::executeDefaultCategories($sql, $categoryName, $user->id);
  };
}

public function addDefaultPaymentMethods()
{
  $user = static::findByEmail($this->email);
  $result = static::getDefaultPaymentMethods();
  foreach ($result as $row) {
    $categoryName = $row['name'];
    $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES (:user_id, :categoryName)';

    static::executeDefaultCategories($sql, $categoryName, $user->id);
  };
}

static public function executeDefaultCategories($sql, $category, $id)
{
  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
  $stmt->bindValue(':categoryName', $category, PDO::PARAM_STR);

  $stmt->execute();
}


static public function getDefaultCategoriesIncomes()
{
  $sql = 'SELECT name FROM incomes_category_default';
  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

static public function getDefaultCategoriesExpenses()
{
  $sql = 'SELECT name FROM expenses_category_default';
  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

static public function getDefaultPaymentMethods()
{
  $sql = 'SELECT name FROM payment_methods_default';
  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
  public function updateProfile($data)
  {
    $this->name = $data['name'];
    $this->email = $data['email'];

    if($data['password'] != ''){
      $this->password = $data['password'];
    }

    $this->validation();

    if(empty($this->errors)){

      $sql = 'UPDATE users
              SET name = :name,
                  email = :email';
                  if(isset($this->password)){
                    $sql .= ', password_hash = :password_hash';
                  }

            $sql .= "\nWHERE id = :id";

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':name',$this->name, PDO::PARAM_STR);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
      $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

      if(isset($this->password)){
          $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
          $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
      }


      return $stmt->execute();
    }
    return false;
  }
*/
}
