<?php
require_once 'course.php';

class DocumentCourse extends Course
{
  private $text_content;

  public function __construct($id, $title = null, $description = null, $teacher_id = null, $image = null, $category_id = null, $tags = null, $text_content = null, $created_at = null)
  {
    parent::__construct($id, $title, $description, $teacher_id, $image, $category_id, $tags, 'document', $created_at);

    $this->text_content = $text_content;
  }

  public function getTextContent()
  {
    return $this->text_content;
  }
  public function setTextContent($text_content)
  {
    $this->text_content = $text_content;
  }

  public function loadCourse(PDO $db)
  {
    $query = "SELECT * FROM courses WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
      $this->title = $course['title'];
      $this->description = $course['description'];
      $this->type = $course['type'];
      $this->category_id = $course['category_id'];
      $this->teacher_id = $course['teacher_id'];
      $this->created_at = $course['created_at'];
      $this->image = $course['image'];
      $this->text_content = $course['text_content'];

      $this->loadTags($db);
      return true;
    } else {
      return false;
    }
  }

  public function saveCourse(PDO $db)
  {
    $query = "INSERT INTO courses (title, description, type, category_id, teacher_id, created_at, image, text_content)
    VALUES (:title, :description, :type, :category_id, :teacher_id, :created_at, :image, :text_content)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->bindParam(':created_at', $this->created_at);
    $stmt->bindParam(':image', $this->image);
    $stmt->bindParam(':text_content', $this->text_content);

    if ($stmt->execute()) {

      $this->id = $db->lastInsertId();
      $this->saveTags($db);
      return true;
    } else {
      return false;
    }
  }

  public function saveCourseUpdate(PDO $db)
  {
    $query = "UPDATE courses SET title = :title, description = :description, category_id = :category_id, image = :image, text_content = :text_content WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':image', $this->image);
    $stmt->bindParam(':text_content', $this->text_content);
    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute()) {
      $this->updateTags($db);
      return true;
    } else {
      return false;
    }
  }
}

?>