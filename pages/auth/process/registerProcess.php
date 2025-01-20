<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/student.php';
require_once '../../../classes/teacher.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['role'])) {
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($role === 'student') {
      $user = new Student($first_name, $last_name, $email, $password);
      $status = $user->register($PDOConn, $confirm_password);
    } elseif ($role === 'teacher') {
      $user = new Teacher($first_name, $last_name, $email, $password);
      $status = $user->register($PDOConn, $confirm_password);
    } else {
      $status = "Invalid role.";
    }

    if ($status === true) {
      $_SESSION['loginSuccess'] = "Account created successfully, you can now login.";
      header("location: ../login.php");
      exit();
    } else {

      $_SESSION['registerError'] = $status;
      header("location: ../register.php");
      exit();
    }

  } else {

    $_SESSION['registerError'] = "All fields are required";
    header("location: ../register.php");
    exit();
  }
} else {

  header("Location: ../../../index.php");
  exit();
}


?>