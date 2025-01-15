<?php
require_once 'course.php';

class DocumentCourse extends Course
{
  public function __construct($id, $title, $description, $content, $category_id, $teacher_id, $created_at = null)
  {
    parent::__construct($id, $title, $description, $content, $category_id, $teacher_id, $created_at);
    $this->type = 'document';
  }
}

?>