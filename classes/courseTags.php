<?php

class CoursTags
{
  private $cours_id;
  private $tag_id;

  public function __construct($cours_id, $tag_id)
  {
    $this->cours_id = $cours_id;
    $this->tag_id = $tag_id;
  }

  public function getCoursId()
  {
    return $this->cours_id;
  }
  public function setCoursId($cours_id)
  {
    $this->cours_id = $cours_id;
  }
  public function getTagId()
  {
    return $this->tag_id;
  }

  public function setTagId($tag_id)
  {
    $this->tag_id = $tag_id;
  }
}

?>