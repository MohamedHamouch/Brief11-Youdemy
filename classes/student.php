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

  //static method
  public static function getAllsutdents(PDO $db)
  {
    $query = "SELECT * FROM users WHERE role = 'student'";
    $stmt = $db->query($query);
    return $stmt->fetchAll();
  }

  // Methods
  public function enrollCourse(PDO $db, Enrollment $en)
  {
    $course_id = $en->getCourseId();
    $enrollment_date = $en->getEnrollmentDate();
    $query = "INSERT INTO enrollments (user_id, course_id, enrollment_date) VALUES (:student_id, :course_id, :enrollment_date)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':student_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->bindParam(':enrollment_date', $enrollment_date, PDO::PARAM_STR);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function cancelEnrollment(PDO $db, Enrollment $en)
  {
    $course_id = $en->getCourseId();
    $query = "DELETE FROM enrollments WHERE user_id = :student_id AND course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':student_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getEnrolledCourses(PDO $db)
  {
    $query = "SELECT c.*, cat.name AS category_name, CONCAT(u.first_name, ' ', u.last_name) AS teacher_name, e.enrollment_date
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN users u ON c.teacher_id = u.id
        LEFT JOIN categories cat ON c.category_id = cat.id
        WHERE e.user_id = :student_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':student_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>