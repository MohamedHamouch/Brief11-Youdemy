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

  public function suspend()
  {
    $this->is_suspended = true;
  }

  public function activate()
  {
    $this->is_suspended = false;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getFullName()
  {
    return $this->first_name . ' ' . $this->last_name;
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
}

?>