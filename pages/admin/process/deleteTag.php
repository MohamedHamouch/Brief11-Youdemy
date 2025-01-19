<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/tag.php';

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

  if (isset($_POST['tagId'])) {
    $tagId = $_POST['tagId'];
    $status = $user->deleteTag($PDOConn, $tagId);
    if ($status) {

      $_SESSION['adminActionSuccess'] = 'Tag deleted successfully';
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = 'Tag deletion failed';
      header('Location: ../adminDashboard.php');
      exit();
    }
  } else {
    $_SESSION['adminActionError'] = 'Tag ID is required';
    header('Location: ../adminDashboard.php');
    exit();
  }
} else {

  $_SESSION['adminActionError'] = 'Invalid request';
  header('Location: ../adminDashboard.php');
  exit();
}


?>