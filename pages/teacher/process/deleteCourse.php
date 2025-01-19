<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/teacher.php';

if (!isset($_SESSION['user'])) {
  header('Location: ../../auth/login.php');
  exit();
} else {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Teacher)) {
    header('Location: ../../auth/login.php');
    exit();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['courseId'])) {
    $course_id = $_POST['courseId'];

    $status = $user->deleteCourse($PDOConn, $course_id);

    if ($status = true) {
      $_SESSION['teacherActionSuccess'] = 'Course deleted successfully.';
      header('Location: ../teacherDashboard.php');
      exit();

    } else {
      $_SESSION['teacherActionError'] = $status;
      header('Location: ../teacherDashboard.php');
      exit();
    }
  } else {

    $_SESSION['teacherActionError'] = 'Course id is required.';
    header('Location: ../teacherDashboard.php');
    exit();
  }
} else {
  $_SESSION['teacherActionError'] = 'Invalid request.';

  header('Location: ../teacherDashboard.php');
  exit();
}

?>