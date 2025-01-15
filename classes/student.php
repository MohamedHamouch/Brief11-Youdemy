<?php

require_once 'user.php';

class Student extends User
{

  public function __construct($first_name, $last_name, $email, $password, $role = 'student', $is_active = true, $is_suspended = false)
  {

    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
    $this->is_active = $is_active;
    $this->is_suspended = $is_suspended;
    $this->created_at = date('Y-m-d H:i:s');
  }

}