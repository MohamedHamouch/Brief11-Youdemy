<?php

abstract class Course
{
  protected $id;
  protected $title;
  protected $description;
  protected $type;
  protected $image;
  protected $category_id;
  protected $teacher_id;
  protected $created_at;
  protected $tags = [];

  public function __construct($id, $title, $description, $teacher_id, $image, $category_id, $tags, $type, $created_at)
  {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->teacher_id = $teacher_id;
    $this->image = $image;
    $this->category_id = $category_id;
    $this->tags = $tags;
    $this->type = $type;
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

  public function getType()
  {
    return $this->type;
  }

  public function getImage()
  {
    return $this->image;
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

  public function getTags()
  {
    return $this->tags;
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

  public function setImage($image)
  {
    $this->image = $image;
  }

  public function setTags($tags)
  {
    $this->tags = $tags;
  }


  //static methods
  public static function getAllCourses(PDO $db)
  {
    $query = "SELECT c.*, u.first_name, u.last_name, cat.name AS category_name
              FROM courses c
              JOIN users u ON c.teacher_id = u.id
              JOIN categories cat ON c.category_id = cat.id
              ORDER BY c.created_at DESC";

    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getLatestCourses(PDO $db, $limit = 4)
  {
    $query = "SELECT c.*, u.first_name, u.last_name, cat.name AS category_name
              FROM courses c
              JOIN users u ON c.teacher_id = u.id
              JOIN categories cat ON c.category_id = cat.id
              ORDER BY c.created_at DESC
              LIMIT :limit";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function filterCourses($pdo, $search = '', $category = '')
  {
    $query = "SELECT c.*, u.first_name, u.last_name, cat.name AS category_name
              FROM courses c
              JOIN categories cat ON c.category_id = cat.id
              JOIN users u ON c.teacher_id = u.id
              WHERE 1";
    $params = [];

    if (!empty($search)) {
      $query .= " AND (title LIKE :search OR c.description LIKE :search)";
      $params[':search'] = "%$search%";
    }
    if (!empty($category)) {
      $query .= " AND category_id = :category";
      $params[':category'] = $category;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function checkCourseType(PDO $db, $course_id)
  {
    $query = "SELECT type FROM courses WHERE id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();

    return $stmt->fetchColumn();
  }


  public static function coursesCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM courses";
    $stmt = $db->query($query);
    return $stmt->fetchColumn();
  }

  //abstract methods

  abstract public function loadCourse(PDO $db);
  abstract public function saveCourse(PDO $db);
  abstract public function saveCourseUpdate(PDO $db);

  //abstract public function getCourseDetails(PDO $db);


  //methods

  public function saveTags(PDO $db)
  {
    $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
    $stmt = $db->prepare($query);

    foreach ($this->tags as $tag) {
      $stmt->bindParam(':course_id', $this->id);
      $stmt->bindParam(':tag_id', $tag);
      $stmt->execute();
    }
  }

  public function loadTags(PDO $db)
  {
    $query = "SELECT tag_id FROM course_tags WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    $this->tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function updateTags(PDO $db)
  {
    $query = "DELETE FROM course_tags WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id);
    $stmt->execute();

    $this->saveTags($db);
  }

  // public function getCourseDetails(PDO $db)
  // {
  //   $query = "
  //        SELECT 
  //            c.id AS course_id,
  //            c.name AS course_name,
  //            c.description AS course_description,
  //            cat.name AS category_name,
  //            CONCAT(u.first_name, ' ', u.last_name) AS user_full_name
  //        FROM Courses c
  //        JOIN Categories cat ON c.category_id = cat.id
  //        JOIN Users u ON c.user_id = u.id
  //        WHERE c.id = :course_id";

  //   $stmt = $db->prepare($query);
  //   $stmt->bindParam(':course_id', $this->id);
  //   $stmt->execute();

  //   return $stmt->fetch(PDO::FETCH_ASSOC);
  // }



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