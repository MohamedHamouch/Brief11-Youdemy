<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/Teacher.php';
require_once '../../../classes/Course.php';
require_once '../../../classes/DocumentCourse.php';
require_once '../../../classes/VideoCourse.php';
require_once '../../../classes/Tag.php';
require_once '../../../classes/Category.php';

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

  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $type = $_POST['type'];
  $category = $_POST['category'];
  $tags = $_POST['tags'] ?? [];

  if (empty($title) || empty($description) || empty($type) || empty($category)) {
    $_SESSION['teacherActionError'] = 'All fields are required';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  //cover image
  if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image'];

    if ($image['size'] > 5 * 1024 * 1024) {
      $_SESSION['teacherActionError'] = 'Cover image size should not exceed 5MB';
      header('Location: ../teacherDashboard.php');
      exit();
    }

    $allowed_image_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($image['type'], $allowed_image_types)) {
      $_SESSION['teacherActionError'] = 'Cover image type not allowed';
      header('Location: ../teacherDashboard.php');
      exit();
    }


    $imageTmpName = $image['tmp_name'];
    $imageName = uniqid("image_") . basename($image['name']);
    $imagePath = '../../../uploads/covers/' . $imageName;

    if (!move_uploaded_file($imageTmpName, $imagePath)) {
      $_SESSION['teacherActionError'] = 'Cover upload failed';
      header('Location: ../teacherDashboard.php');
      exit();
    }
  } else {
    $_SESSION['teacherActionError'] = 'Cover image is required';
    header('Location: ../teacherDashboard.php');
    exit();
  }

  //types deff
  if ($type === 'document') {
    $textContent = trim($_POST['text']);
    if (empty($textContent)) {
      $_SESSION['teacherActionError'] = 'Text content is required for a document course';
      header('Location: ../teacherDashboard.php');
      exit();
    }
    $course = new DocumentCourse(null, $title, $description, $user->getId(), $imageName, $category, $tags, $textContent);

  } elseif ($type === 'video') {

    if (empty($_FILES['video']['name'])) {
      $_SESSION['teacherActionError'] = 'Video is required for a video course';
      header('Location: ../teacherDashboard.php');
      exit();
    }

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

    $course = new VideoCourse(null, $title, $description, $user->getId(), $imageName, $category, $tags, $videoPath);
    var_dump($video);

  } else {
    $_SESSION['teacherActionError'] = 'Invalid course type';
    header('Location: ../teacherDashboard.php');
    exit();
  }


  $status = $user->addCourse($PDOConn, $course);
  if ($status === false) {
    $_SESSION['teacherActionError'] = 'Course upload failed';
    header('Location: ../teacherDashboard.php');
    exit();
  } else {
    $_SESSION['teacherActionSuccess'] = 'Course uploaded successfully';
    header('Location: ../teacherDashboard.php');
    exit();
  }


} else {
  $_SESSION['teacherActionError'] = 'Invalid request';
  header('Location: ../teacherDashboard.php');
  exit();
}
