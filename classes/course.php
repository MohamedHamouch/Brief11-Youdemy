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
    $query = "SELECT c.*, 
          COALESCE(CONCAT(u.first_name, ' ', u.last_name), 'Deleted Account') AS teacher_name,
          COALESCE(cat.name, 'General') AS category_name
          FROM courses c
          LEFT JOIN users u ON c.teacher_id = u.id
          LEFT JOIN categories cat ON c.category_id = cat.id
          ORDER BY c.created_at DESC";

    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function handelPagination(PDO $db, $page, $limit)
  {
    $offset = ($page - 1) * $limit;

    $query = "SELECT c.*, 
          COALESCE(CONCAT(u.first_name, ' ', u.last_name), 'Deleted Account') AS teacher_name,
          COALESCE(cat.name, 'General') AS category_name
          FROM courses c
          LEFT JOIN users u ON c.teacher_id = u.id
          LEFT JOIN categories cat ON c.category_id = cat.id
          ORDER BY c.created_at DESC
          LIMIT :limit OFFSET :offset;";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getLatestCourses(PDO $db, $limit = 4)
  {
    $query = "SELECT c.*, 
          COALESCE(CONCAT(u.first_name, ' ', u.last_name), 'Deleted Account') AS teacher_name,
          COALESCE(cat.name, 'General') AS category_name
          FROM courses c
          LEFT JOIN users u ON c.teacher_id = u.id
          LEFT JOIN categories cat ON c.category_id = cat.id
          ORDER BY c.created_at DESC
          LIMIT :limit";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function filterCourses($pdo, $search = '', $category = '')
  {
    $query = "SELECT c.*, 
              COALESCE(CONCAT(u.first_name, ' ', u.last_name), 'Delete Account')AS teacher_name,
              COALESCE(cat.name, 'General') AS category_name
              FROM courses c
              LEFT JOIN categories cat ON c.category_id = cat.id
              LEFT JOIN users u ON c.teacher_id = u.id
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

    $query .= " ORDER BY c.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function checkCourseType(PDO $db, $course_id)
  {
    $query = "SELECT type FROM courses WHERE id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public static function checkCourseExsistance(PDO $db, $course_id)
  {
    $query = "SELECT COUNT(*) FROM courses WHERE id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
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

  //methods

  public function saveTags(PDO $db)
  {
    $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
    $stmt = $db->prepare($query);

    foreach ($this->tags as $tag) {
      $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
      $stmt->bindParam(':tag_id', $tag, PDO::PARAM_STR);
      $stmt->execute();
    }
  }

  public function loadTags(PDO $db)
  {
    $query = "SELECT tag_id FROM course_tags WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $this->tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function updateTags(PDO $db)
  {
    $query = "DELETE FROM course_tags WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $this->saveTags($db);
  }

  public function getCourseTeacherName(PDO $db)
  {
    $query = "SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE id = :teacher_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() ?: 'Deleted Account';
  }
  public function getCourseTags(PDO $db)
  {
    $query = "SELECT t.name AS tag_name
         FROM Course_Tags ct
         JOIN Tags t ON ct.tag_id = t.id
         WHERE ct.course_id = :course_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCourseCategoryName(PDO $db)
  {
    $query = "SELECT name FROM categories WHERE id = :category_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() ?: 'General';
  }

  public function courseEnrollmentCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM Enrollments WHERE course_id = :course_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function checkUserEnrollment(PDO $db, $user_id)
  {
    $query = "SELECT COUNT(*) FROM Enrollments WHERE course_id = :course_id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
  }

  public function getCourseEnrollments(PDO $db)
  {
    $query = "SELECT u.id AS user_id, u.first_name, u.last_name
         FROM Enrollments e
         JOIN Users u ON e.user_id = u.id
         WHERE e.course_id = :course_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


}
?>