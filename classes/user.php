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

  public function setFirstName($first_name)
  {
    $this->first_name = $first_name;
  }

  public function setLastName($last_name)
  {
    $this->last_name = $last_name;
  }

  //static methods
  public static function getUserRole($db, $email)
  {
    $query = "SELECT role FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    return $role['role'];
  }

  public static function getActiveUsers($db)
  {
    $query = "SELECT * FROM users WHERE is_active = 1 AND is_suspended = 0";
    $stmt = $db->query($query);
    return $stmt->fetchAll();
  }

  public static function getSuspendedUsers($db)
  {
    $query = "SELECT * FROM users WHERE is_suspended = 1";
    $stmt = $db->query($query);
    return $stmt->fetchAll();
  }

  //methods
  public function loadUserByEmail($db)
  {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
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
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
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

  public function updateProfile($db)
  {
    $query = "UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function login($db)
  {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
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
    return true;
  }

}

?>