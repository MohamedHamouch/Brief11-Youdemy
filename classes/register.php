<?php

trait Register
{
  public function register(PDO $db, $confirmPass)
  {
    if ($this->email && $this->first_name && $this->last_name && $this->password) {

      if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
      }

      $query = "SELECT id FROM users WHERE email = :email";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->fetchColumn()) {
        return "This email is already registered.";
      }

      if ($this->password !== $confirmPass) {
        return "Passwords do not match.";
      }
      $is_active = $this->is_active ? 1 : 0;
      $is_suspended = $this->is_suspended ? 1 : 0;
      $hashedPass = password_hash($this->password, PASSWORD_DEFAULT);

      $query = "INSERT INTO users (email, first_name, last_name, password, role, is_active, is_suspended) 
                VALUES (:email, :first_name, :last_name, :password, :role, :is_active, :is_suspended)";

      $stmt = $db->prepare($query);
      $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
      $stmt->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
      $stmt->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
      $stmt->bindParam(':password', $hashedPass, PDO::PARAM_STR);
      $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);
      $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
      $stmt->bindParam(':is_suspended', $is_suspended, PDO::PARAM_INT);

      if ($stmt->execute()) {
        return true;
      } else {
        return "Failed to register user.";
      }
    } else {
      return "All fields are required.";
    }
  }
}

?>