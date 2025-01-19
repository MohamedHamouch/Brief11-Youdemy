<?php
class Comment
{
  private $id;
  private $user_id;
  private $course_id;
  private $content;
  private $created_at;

  public function __construct($id, $user_id, $course_id, $content, $created_at = null)
  {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->course_id = $course_id;
    $this->content = $content;
    $this->created_at = $created_at ?? date('Y-m-d H:i:s');
  }

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

  public function getContent()
  {
    return $this->content;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setCreatedAt($created_at)
  {

    $this->created_at = $created_at;
  }
}
?>