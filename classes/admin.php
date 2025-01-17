<?php

require_once 'user.php';

class Admin extends User
{

  public function __construct($first_name, $last_name, $email, $password, $role = 'admin', $is_active = true, $is_suspended = false)
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

  public function getAllUsers(PDO $db)
  {
    $query = "SELECT * FROM users";
    $stmt = $db->query($query);
    return $stmt->fetchAll();
  }

  public function activateUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_active = 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
  }

  public function suspendUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_suspended = 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
  }

  public function unsuspendUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_suspended = 0 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
  }

  public function deleteUser(PDO $db, $user_id)
  {
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
  }

  public function addCategory(PDO $db, Category $category)
  {
    $categoryName = $category->getName();
    $categoryDescription = $category->getDescription();

    $query = "SELECT COUNT(*) FROM categories WHERE name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $categoryName);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    if ($count > 0) {

      return "Failed to add, '$categoryName' already exists.";
    } else {

      $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $categoryName);
      $stmt->bindParam(':description', $categoryDescription);
      if ($stmt->execute()) {
        return "'$categoryName' category added successfully.";
      } else {
        return "Failed to add '$categoryName' category.";
      }
    }
  }

  public function deleteCategory(PDO $db, $category_id)
  {
    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $category_id);
    $stmt->execute();
  }

  public function addTag(PDO $db, Tag $tag)
  {
    $tagName = $tag->getName();

    $query = "SELECT COUNT(*) FROM tags WHERE name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $tagName);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    if ($count > 0) {

      return "Failed to add, '$tagName' already exists.";
    } else {

      $query = "INSERT INTO tags (name) VALUES (:name)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $tagName);

      if ($stmt->execute()) {
        return "'$tagName' tag added successfully.";
      } else {
        return "Failed to add '$tagName' tag.";
      }
    }
  }

  public function deleteTag(PDO $db, $tag_id)
  {
    $query = "DELETE FROM tags WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $tag_id);
    $stmt->execute();
  }
}