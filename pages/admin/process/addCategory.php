<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/Admin.php';
require_once '../../../classes/Category.php';

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

  if (isset($_POST['categoryName']) && isset($_POST['categoryDescription'])) {
    $categoryName = trim($_POST['categoryName']);
    $categoryDescription = trim($_POST['categoryDescription']);
    if (empty($categoryName) || empty($categoryDescription)) {
      $_SESSION['adminActionError'] = 'Category name and description cannot be empty';
      header('Location: ../adminDashboard.php');
      exit();
    }
    $category = new Category(null, $categoryName, $categoryDescription);
    $status = $user->addCategory($PDOConn, $category);
    if ($status === true) {
      $_SESSION['adminActionSuccess'] = "$categoryName category added successfully";
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = $status;
      header('Location: ../adminDashboard.php');
      exit();
    }
  } else {
    $_SESSION['adminActionError'] = 'Category name and description are required';
    header('Location: ../adminDashboard.php');
    exit();
  }
} else {
  $_SESSION['adminActionError'] = 'Invalid request';
  header('Location: ../adminDashboard.php');
  exit();
}