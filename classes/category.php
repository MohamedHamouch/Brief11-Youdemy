<?php
class Category
{
  private $id;
  private $name;
  private $description;

  public function __construct($id, $name, $description = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
  }

  public static function getAllCategories(PDO $db)
  {
    $query = "SELECT * FROM categories";
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

  public function getDescription()
  {
    return $this->description;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }
}

?>