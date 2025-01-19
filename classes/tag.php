<?php
class Tag
{
  private $id;
  private $name;

  public function __construct($id, $name)
  {
    $this->id = $id;
    $this->name = $name;
  }

  public static function tagCount(PDO $db)
  {
    $query = "SELECT COUNT(*) FROM tags";
    $stmt = $db->query($query);
    return $stmt->fetchColumn();
  }

  public static function getAllTags(PDO $db)
  {
    $query = "SELECT * FROM tags";
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }
}
?>