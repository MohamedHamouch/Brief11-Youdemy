<?php
abstract class User
{
  protected $id;
  protected $first_name;
  protected $last_name;
  protected $email;
  protected $password;
  protected $role;
  protected $is_active;
  protected $is_suspended;
  protected $created_at;

  public function __tostring()
  {
    return "$this->first_name $this->last_name";
  }

  public function getId()
  {
    return $this->id;
  }

  public function getFullName()
  {
    return "$this->first_name $this->last_name";
  }
  public function getFirstName()
  {
    return $this->first_name;
  }

  public function getLastName()
  {
    return $this->last_name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getRole()
  {
    return $this->role;
  }

  public function isActive()
  {
    return $this->is_active;
  }

  public function isSuspended()
  {
    return $this->is_suspended;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  public function loadUserByEmail($db)
  {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $this->email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = $user['id'];
    $this->first_name = $user['first_name'];
    $this->last_name = $user['last_name'];
    $this->email = $user['email'];
    $this->role = $user['role'];
    $this->created_at = $user['created_at'];
    $this->is_active = $user['is_active'];
    $this->is_suspended = $user['is_suspended'];

    return $user;
  }

  public function loadUserById($db)
  {
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = $user['id'];
    $this->first_name = $user['first_name'];
    $this->last_name = $user['last_name'];
    $this->email = $user['email'];
    $this->role = $user['role'];
    $this->created_at = $user['created_at'];
    $this->is_active = $user['is_active'];
    $this->is_suspended = $user['is_suspended'];

    return $user;
  }
  public function login($db)
  {
    $query = "SELECT * FROM user WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $this->email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($this->password, $user['password'])) {
      return "Invalid email or password.";
    }

    return true;
  }

  public function logout()
  {
    session_unset();
    session_destroy();
    return header('Location: login.php');
  }

}

?>