<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/category.php';

if (!isset($_SESSION['user'])) {
  header('Location: ../../auth/login.php');
  exit();
} else {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Admin)) {
    header('Location: ../../auth/login.php');
    exit();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['categoryId']) && isset($_POST['categoryName']) && isset($_POST['categoryDescription'])) {
    $categoryId = trim($_POST['categoryId']);
    $categoryName = trim($_POST['categoryName']);
    $categoryDescription = trim($_POST['categoryDescription']);

    if (empty($categoryName) || empty($categoryDescription)) {
      $_SESSION['adminActionError'] = 'Category Name and Description cannot be empty';
      header('Location: ../adminDashboard.php');
      exit();
    }

    $category = new Category($categoryId, $categoryName, $categoryDescription);
    $status = $user->updateCategory($PDOConn, $category);
    if ($status) {

      $_SESSION['adminActionSuccess'] = 'Category updated successfully';
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = $status;
      header('Location: ../adminDashboard.php');
      exit();
    }
  } else {
    $_SESSION['adminActionError'] = 'Category ID, Name and Description are required';
    header('Location: ../adminDashboard.php');
    exit();
  }

} else {

  $_SESSION['adminActionError'] = 'invalid request';
  header('Location: ../adminDashboard.php');
  exit();
}
?>