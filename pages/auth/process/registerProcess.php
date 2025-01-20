<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/student.php';
require_once '../../../classes/teacher.php';

if (isset($_SESSION['user'])) {
  header("Location: ../../../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['role'])) {
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);


    if (empty($email)) {
      $_SESSION['registerError'] = "Email is required.";
      header("location: ../register.php");
      exit();
    }
    if (empty($first_name)) {
      $_SESSION['registerError'] = "First name is required.";
      header("location: ../register.php");
      exit();
    }
    if (empty($last_name)) {

      $_SESSION['registerError'] = "Last name is required.";
      header("location: ../register.php");
      exit();
    }
    if (empty($role)) {
      $_SESSION['registerError'] = "Role is required.";
      header("location: ../register.php");
      exit();
    }
    if (empty($password)) {
      $_SESSION['registerError'] = "Password is required.";
      header("location: ../register.php");
      exit();
    }
    if (empty($confirm_password)) {
      $_SESSION['registerError'] = "Confirm Password is required.";
      header("location: ../register.php");
      exit();
    }

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

  $_SESSION['registerError'] = "Invalid request";
  header("location: ../register.php");
  exit();
}


?>