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

  public function __construct($id, $title, $description, $content, $category_id, $teacher_id, $created_at = null)
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

  public function setCreatedAt($created_at)
  {
    $this->created_at = $created_at;
  }
}
?>