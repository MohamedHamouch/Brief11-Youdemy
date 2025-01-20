<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/student.php';
require_once '../../../classes/enrollment.php';


if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Student)) {
    header('Location: ../../../index.php');
    exit();
  }
} else {
  header('Location: ../auth/login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['courseId'])) {
    $course_id = $_POST['courseId'];
    $user_id = $user->getId();

    $enrollment = new Enrollment(null, $course_id, $user_id);
    $status = $user->cancelEnrollment($PDOConn, $enrollment);
    if ($status) {

      $_SESSION['enrollSuccess'] = 'You successfully canceled the enrollment ';
      header('Location: ../myCourses.php');
      exit();
    } else {
      $_SESSION['enrollError'] = 'Failed to cancel enrollment. Please try again later.';
      header('Location: ../myCourses.php');
      exit();
    }

  } else {
    $_SESSION['enrollError'] = 'Please choose a valid course.';
    header('Location: ../myCourses.php');
    exit();
  }
} else {
  $_SESSION['enrollError'] = 'Invalid request.';
  header('Location: ../myCourses.php');
  exit();
}