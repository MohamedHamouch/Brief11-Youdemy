<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/teacher.php';
require_once '../../../classes/course.php';
require_once '../../../classes/documentCourse.php';
require_once '../../../classes/videoCourse.php';
require_once '../../../classes/tag.php';
require_once '../../../classes/category.php';

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
  if (!isset($_POST['courseId'])) {

    $_SESSION['teacherActionError'] = 'Course not found';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  if (empty(trim($_POST['title'])) || empty(trim($_POST['description']))) {
    $_SESSION['teacherActionError'] = 'Both title and description are required';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  if (empty($_POST['tags']) || !isset($_POST['category'])) {
    $_SESSION['teacherActionError'] = 'Category and tags are required';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  if (!isset($_POST['type'])) {
    $_SESSION['teacherActionError'] = 'Type is required';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  $course_id = $_POST['courseId'];
  $type = trim($_POST['type']);

  if ($type == 'video') {
    $course = new VideoCourse($course_id);
    $course->loadCourse($PDOConn);

    $course->setTitle(trim($_POST['title']));
    $course->setDescription(trim($_POST['description']));
    $course->setCategoryId($_POST['category']);
    $course->setTags($_POST['tags']);

    if (!empty($_FILES['video']['name'])) {

      $video = $_FILES['video'];

      if ($video['size'] > 100 * 1024 * 1024) {
        $_SESSION['teacherActionError'] = 'Video size should not exceed 100MB';
        header('Location: ../teacherDashboard.php');
        exit();
      }

      $allowed_video_types = ['video/mp4', 'video/webm', 'video/x-matroska'];
      if (!in_array($video['type'], $allowed_video_types)) {
        $_SESSION['teacherActionError'] = 'Video type not allowed';
        header('Location: ../teacherDashboard.php');
        exit();
      }

      $videoTmpName = $video['tmp_name'];
      $videoName = uniqid('video_') . basename($video['name']);
      $videoPath = '../../../uploads/videos/' . $videoName;
      if (!move_uploaded_file($videoTmpName, $videoPath)) {
        $_SESSION['teacherActionError'] = 'Video upload failed';
        header('Location: ../teacherDashboard.php');
        exit();
      }
      $course->setVideoPath($videoName);
    }

  } elseif ($type == 'document') {

    if (empty(trim($_POST['text']))) {
      $_SESSION['teacherActionError'] = 'Text content is required';
      header('Location: ../teacherDashboard.php');
      exit();
    }

    $course = new DocumentCourse($course_id);
    $course->loadCourse($PDOConn);

    $course->setTitle(trim($_POST['title']));
    $course->setDescription(trim($_POST['description']));
    $course->setCategoryId($_POST['category']);
    $course->setTags($_POST['tags']);
    $course->setTextContent(trim($_POST['text']));

  } else {

    $_SESSION['teacherActionError'] = 'Invalid course type';
    header('Location: ../teacherDashboard.php');
    exit();
  }


  if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image'];

    if ($image['size'] > 5 * 1024 * 1024) {
      $_SESSION['teacherActionError'] = 'Image size should not exceed 5MB';
      header('Location: ../teacherDashboard.php');
      exit();
    }

    $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_image_types)) {
      $_SESSION['teacherActionError'] = 'Image type not allowed';
      header('Location: ../teacherDashboard.php');
      exit();
    }

    $imageTmpName = $image['tmp_name'];
    $imageName = uniqid('image_') . basename($image['name']);
    $imagePath = '../../../uploads/images/' . $imageName;
    if (!move_uploaded_file($imageTmpName, $imagePath)) {
      $_SESSION['teacherActionError'] = 'Image upload failed';
      header('Location: ../teacherDashboard.php');
      exit();
    }
    $course->setImage($imageName);
  }

  $status = $user->editCourse($PDOConn, $course);

  if ($status) {

    $_SESSION['teacherActionSuccess'] = 'Course updated successfully';
    header('Location: ../teacherDashboard.php');
    exit();

  } else {
    $_SESSION['teacherActionError'] = 'Course update failed';
    header('Location: ../teacherDashboard.php');
    exit();
  }


} else {
  $_SESSION['teacherActionError'] = 'Invalid request';
  header('Location: ../teacherDashboard.php');
  exit();
}