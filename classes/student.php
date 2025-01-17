<?php

require_once 'user.php';
require_once 'register.php';
class Student extends User
{
  use Register;

  public function __construct($first_name, $last_name, $email, $password = null, $role = 'student', $is_active = true, $is_suspended = false)
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

  public static function getAllsutdents(PDO $db)
  {
    $query = "SELECT * FROM users WHERE role = 'student'";
    $stmt = $db->query($query);
    return $stmt->fetchAll();
  }
  public function enrollCourse(PDO $db, $course_id)
  {
    $query = "INSERT INTO enrollment (student_id, course_id) VALUES (:student_id, :course_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':student_id', $this->id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
  }

}

?>