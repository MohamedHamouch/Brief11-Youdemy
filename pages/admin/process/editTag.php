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

  if (isset($_POST['tagId']) && isset($_POST['tagName'])) {
    $tagId = trim($_POST['tagId']);
    $tagName = trim($_POST['tagName']);
    if (empty($tagName)) {
      $_SESSION['adminActionError'] = 'Tag Name cannot be empty';
      header('Location: ../adminDashboard.php');
      exit();
    }

    $tag = new Tag($tagId, $tagName);
    $status = $user->updateTag($PDOConn, $tag);
    if ($status) {

      $_SESSION['adminActionSuccess'] = 'Tag updated successfully';
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = $status;
      header('Location: ../adminDashboard.php');
      exit();
    }

  } else {
    $_SESSION['adminActionError'] = 'Tag ID and Tag Name are required';
    header('Location: ../adminDashboard.php');
    exit();
  }
} else {

  $_SESSION['adminActionError'] = 'invalid request';
  header('Location: ../adminDashboard.php');
  exit();
}

?>