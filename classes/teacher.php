<?php

require_once 'user.php';
require_once 'register.php';

class Teacher extends User
{
  use Register;

  public function __construct($first_name, $last_name, $email, $password = null, $role = 'teacher', $is_active = false, $is_suspended = false)
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

  //static methods
  public static function getPendingTeachers(PDO $db)
  {
    $query = "SELECT * FROM users WHERE role = 'teacher' AND is_active = 0";
    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function teachersCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM users WHERE role = 'teacher'";
    $stmt = $db->query($query);
    return $stmt->fetchColumn();
  }

  //methods
  public function addCourse(PDO $db, Course $course)
  {
    return $course->saveCourse($db);
  }

  public function editCourse(PDO $db, Course $course)
  {
    return $course->saveCourseUpdate($db);
  }

  public function getTeacherCourses(PDO $db)
  {
    $query = "SELECT c.*, COALESCE(cat.name, 'General') AS category_name
              FROM courses c
              LEFT JOIN categories cat ON c.category_id = cat.id
              WHERE c.teacher_id = :teacher_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':teacher_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deleteCourse(PDO $db, $course_id)
  {
    $query = "DELETE FROM course_tags WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();


    $query = "DELETE FROM courses WHERE id = :course_id AND teacher_id = :teacher_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $this->id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return true;
    } else {
      return "Failed to delete course";
    }
  }
}