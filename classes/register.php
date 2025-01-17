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
      $stmt->bindParam(':email', $this->email);
      $stmt->execute();

      if ($stmt->fetchColumn()) {
        return "This email is already registered.";
      }

      if ($this->password !== $confirmPass) {
        return "Passwords do not match.";
      }

      $hashedPass = password_hash($this->password, PASSWORD_DEFAULT);

      $query = "INSERT INTO users (email, first_name, last_name, password) 
                VALUES (:email, :first_name, :last_name, :password)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':first_name', $this->first_name);
      $stmt->bindParam(':last_name', $this->last_name);
      $stmt->bindParam(':password', $hashedPass);

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