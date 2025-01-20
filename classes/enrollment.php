<?php

class Enrollment
{
  private $id;
  private $user_id;
  private $course_id;
  private $enrollment_date;

  public function __construct($id, $user_id, $course_id, $enrollment_date = null)
  {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->course_id = $course_id;
    $this->enrollment_date = $enrollment_date ?? date('Y-m-d H:i:s');
  }

  // Getters
  public function getId()
  {
    return $this->id;
  }

  public function getUserId()
  {
    return $this->user_id;
  }

  public function getCourseId()
  {
    return $this->course_id;
  }

  public function getEnrollmentDate()
  {
    return $this->enrollment_date;
  }

  // Setters
  public function setUserId($user_id)
  {
    $this->user_id = $user_id;
  }

  public function setCourseId($course_id)
  {
    $this->course_id = $course_id;
  }

  public function setEnrollmentDate($enrollment_date)
  {
    $this->enrollment_date = $enrollment_date;
  }

  public static function enrollmentsCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM enrollments";
    $stmt = $db->query($query);

    return $stmt->fetchColumn();
  }
}

?>