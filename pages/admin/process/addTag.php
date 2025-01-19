<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/Admin.php';
require_once '../../../classes/Tag.php';

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
  if (isset($_POST['tagName'])) {
    $tagName = trim($_POST['tagName']);

    if (empty($tagName)) {
      $_SESSION['adminActionError'] = 'Tag name cannot be empty';
      header('Location: ../adminDashboard.php');
      exit();
    }
    $tag = new Tag(null, $tagName);
    $status = $user->addTag($PDOConn, $tag);
    if ($status === true) {
      $_SESSION['adminActionSuccess'] = "$tagName tag added successfully";
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = $status;
      header('Location: ../adminDashboard.php');
      exit();
    }
  } else {
    $_SESSION['adminActionError'] = 'Tag name is required';
    header('Location: ../adminDashboard.php');
    exit();
  }

} else {
  $_SESSION['adminActionError'] = 'Invalid request';
  header('Location: ../adminDashboard.php');
  exit();
}
?>