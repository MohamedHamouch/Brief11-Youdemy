<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/user.php';
require_once '../../../classes/student.php';
require_once '../../../classes/teacher.php';
require_once '../../../classes/admin.php';

if (isset($_SESSION['user'])) {
  header("Location: ../../../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
      $_SESSION['loginError'] = "Email is required.";
      header("location: ../login.php");
      exit();
    }
    if (empty($password)) {
      $_SESSION['loginError'] = "Password is required.";
      header("location: ../login.php");
      exit();
    }

    $role = User::getUserRole($PDOConn, $email);
    if ($role === 'student') {

      $user = new Student(null, null, $email, $password);
      $user->loadUserByEmail($PDOConn);
      $status = $user->login($PDOConn);
    } elseif ($role === 'teacher') {

      $user = new Teacher(null, null, $email, $password);
      $user->loadUserByEmail($PDOConn);
      $status = $user->login($PDOConn);
    } elseif ($role === 'admin') {

      $user = new Admin(null, null, $email, $password);
      $user->loadUserByEmail($PDOConn);
      $status = $user->login($PDOConn);
    } else {

      $status = "Invalid email or password.";
    }



    if ($status === true) {
      
      $_SESSION['user'] = serialize($user);
      header("Location: ../../../index.php");
      exit();
    } else {

      $_SESSION['loginError'] = $status;
      header('Location: ../login.php');
      exit();
    }

  } else {

    $_SESSION['loginError'] = "All fields are required";
    header('Location: ../login.php');
    exit();
  }
} else {

  $_SESSION['loginError'] = "Invalid request";
  header("location: ../register.php");
  exit();
}


?>