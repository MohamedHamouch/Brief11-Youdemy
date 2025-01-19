<?php
require_once 'course.php';

class videoCourse extends Course
{
  private $video_path;
  public function __construct($id, $title = null, $description = null, $teacher_id = null, $image = null, $category_id = null, $tags = null, $video_path = null, $created_at = null)
  {
    parent::__construct($id, $title, $description, $teacher_id, $image, $category_id, $tags, 'video', $created_at);
    $this->video_path = $video_path;
  }
  public function getVideoPath()
  {
    return $this->video_path;
  }
  public function setVideoPath($video_path)
  {
    $this->video_path = $video_path;
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
      $this->video_path = $course['video_path'];

      $this->loadTags($db);
      return true;
    } else {
      return false;
    }
  }

  public function saveCourse(PDO $db)
  {
    $query = "INSERT INTO courses (title, description, type, category_id, teacher_id, created_at, image, video_path) 
              VALUES (:title, :description, :type, :category_id, :teacher_id, :created_at, :image, :video_path)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->bindParam(':created_at', $this->created_at);
    $stmt->bindParam(':image', $this->image);
    $stmt->bindParam(':video_path', $this->video_path);

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
    $query = "UPDATE courses SET title = :title, description = :description, category_id = :category_id, image = :image, video_path = :video_path WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':image', $this->image);
    $stmt->bindParam(':video_path', $this->video_path);
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