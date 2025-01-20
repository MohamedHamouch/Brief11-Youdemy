<?php

require_once 'user.php';

class Admin extends User
{

  public function __construct($first_name, $last_name, $email, $password = null, $role = 'admin', $is_active = true, $is_suspended = false)
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

  public function filterActiveUsers(array $users, string $name = '', string $role = ''): array
  {
    $filteredUsers = [];

    foreach ($users as $user) {

      if (!empty($name)) {

        $fullName = "{$user['first_name']} {$user['last_name']}";
        $nameMatches = str_contains($fullName, $name) !== false;
      } else {
        $nameMatches = true;
      }

      if (!empty($role)) {
        $roleMatches = $user['role'] === $role;
      } else {
        $roleMatches = true;
      }

      if ($nameMatches && $roleMatches) {
        $filteredUsers[] = $user;
      }
    }
    return $filteredUsers;
  }

  public function activateUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_active = 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function suspendUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_suspended = 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function unsuspendUser(PDO $db, $user_id)
  {
    $query = "UPDATE users SET is_suspended = 0 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteUser(PDO $db, $user_id)
  {
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function addCategory(PDO $db, Category $category)
  {
    $categoryName = $category->getName();
    $categoryDescription = $category->getDescription();

    $query = "SELECT COUNT(*) FROM categories WHERE name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    if ($count > 0) {

      return "Failed to add, '$categoryName' already exists.";
    } else {

      $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
      $stmt->bindParam(':description', $categoryDescription, PDO::PARAM_STR);
      if ($stmt->execute()) {
        return true;
      } else {
        return "Failed to add '$categoryName' category.";
      }
    }
  }

  public function updateCategory(PDO $db, Category $category)
  {
    $categoryName = $category->getName();
    $categoryDescription = $category->getDescription();
    $categoryId = $category->getId();

    $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
    $stmt->bindParam(':description', $categoryDescription, PDO::PARAM_STR);
    $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return true;
    } else {
      return "Failed to update '$categoryName' category.";
    }
  }

  public function deleteCategory(PDO $db, $category_id)
  {
    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return true;
    } else {
      return "Failed to delete category.";
    }
  }

  public function addTag(PDO $db, Tag $tag)
  {
    $tagName = $tag->getName();

    $query = "SELECT COUNT(*) FROM tags WHERE name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $tagName, PDO::PARAM_STR);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    if ($count > 0) {

      return "Failed to add, '$tagName' already exists.";
    } else {

      $query = "INSERT INTO tags (name) VALUES (:name)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':name', $tagName, PDO::PARAM_STR);

      if ($stmt->execute()) {
        return true;
      } else {
        return "Failed to add '$tagName' tag.";
      }
    }
  }

  public function updateTag(PDO $db, Tag $tag)
  {
    $tagName = $tag->getName();
    $tagId = $tag->getId();

    $query = "UPDATE tags SET name = :name WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $tagName, PDO::PARAM_STR);
    $stmt->bindParam(':id', $tagId, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return true;
    } else {
      return "Failed to update '$tagName' tag.";
    }
  }

  public function deleteTag(PDO $db, $tag_id)
  {
    $query = "DELETE FROM tags WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $tag_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return true;
    } else {
      return "Failed to delete tag.";
    }
  }
}