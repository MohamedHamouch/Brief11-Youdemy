<?php

abstract class Course
{
  protected $id;
  protected $title;
  protected $description;
  protected $content;
  protected $type;
  protected $category_id;
  protected $teacher_id;
  protected $created_at;

  public function __construct($id, $title = null, $description = null, $content = null, $category_id = null, $teacher_id = null, $created_at = null)
  {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->content = $content;
    $this->category_id = $category_id;
    $this->teacher_id = $teacher_id;
    $this->created_at = $created_at ?? date('Y-m-d H:i:s');
  }

  //getters
  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getCategoryId()
  {
    return $this->category_id;
  }

  public function getTeacherId()
  {
    return $this->teacher_id;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  // Setters
  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setType($type)
  {
    $this->type = $type;
  }

  public function setCategoryId($category_id)
  {
    $this->category_id = $category_id;
  }

  public function setTeacherId($teacher_id)
  {
    $this->teacher_id = $teacher_id;
  }


  //static methods
  public static function getAllCourses(PDO $db)
  {
    $query = "SELECT * FROM courses";
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getLatestCourses(PDO $db, $limit = 4)
  {
    $query = "SELECT * FROM courses ORDER BY created_at DESC LIMIT :limit";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function coursesCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM courses";
    $stmt = $db->query($query);
    return $stmt->fetchColumn();
  }

  //methods
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
      $this->content = $course['content'];
      $this->type = $course['type'];
      $this->category_id = $course['category_id'];
      $this->teacher_id = $course['teacher_id'];
      $this->created_at = $course['created_at'];
    }

    return $course;
  }

  public function getCourseDetails(PDO $db)
  {
    $query = "
         SELECT 
             c.id AS course_id,
             c.name AS course_name,
             c.description AS course_description,
             cat.name AS category_name,
             CONCAT(u.first_name, ' ', u.last_name) AS user_full_name
         FROM Courses c
         JOIN Categories cat ON c.category_id = cat.id
         JOIN Users u ON c.user_id = u.id
         WHERE c.id = :course_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getCourseTags(PDO $db)
  {
    $query = "
          SELECT t.name AS tag_name
         FROM Course_Tags ct
         JOIN Tags t ON ct.tag_id = t.id
         WHERE ct.course_id = :course_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCourseEnrollments(PDO $db)
  {
    $query = "SELECT u.id AS user_id, u.first_name, u.last_name
         FROM Enrollments e
         JOIN Users u ON e.user_id = u.id
         WHERE e.course_id = :course_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCourseComments(PDO $db)
  {
    $query = "
         SELECT 
             c.id AS comment_id,
             c.content AS comment_content,
             c.created_at AS comment_date,
             u.id AS user_id,
             u.first_name,
             u.last_name,
             u.role
         FROM comments c
         JOIN Users u ON c.user_id = u.id
         WHERE c.course_id = :course_id
         ORDER BY c.created_at DESC";


    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}
?>