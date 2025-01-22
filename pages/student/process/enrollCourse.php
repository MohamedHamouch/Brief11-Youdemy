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

    $enrollment = new Enrollment(null, $user_id, $course_id);
    $status = $user->enrollCourse($PDOConn, $enrollment);
    if ($status) {

      $_SESSION['enrollSuccess'] = 'You have successfully enrolled in the course!';
      header('Location: ../myCourses.php');
      exit();
    } else {
      $_SESSION['enrollError'] = 'Failed to enroll. Please try again later.';
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