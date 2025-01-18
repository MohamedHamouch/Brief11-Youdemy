<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/teacher.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Teacher)) {
    header('Location: ../../index.php');
    exit();
  }
} else {
  header('Location: ../auth/login.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Teacher Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
  <header class="w-full">
    <div class="bg-white backdrop-blur-md bg-opacity-90">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-20">
          <div class="flex items-center">
            <div
              class="text-2xl font-bold bg-gradient-to-r from-orange-500 to-orange-600 text-transparent bg-clip-text">
              Youdemy
            </div>
          </div>

          <nav class="flex items-center">
            <ul class="hidden md:flex items-center space-x-6">
              <li>
                <a href="../../index.php"
                  class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Home</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Contact</a>
              </li>
              <li class="relative">
                <button id="dropdownButton"
                  class="flex items-center space-x-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-full hover:bg-orange-100 transition-colors duration-300">
                  <i class="fas fa-user-circle text-lg"></i>
                  <span><?= $user ?></span>
                  <i class="fas fa-chevron-down text-sm"></i>
                </button>
                <div id="dropdownMenu"
                  class="hidden w-full absolute mt-2 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                  <a href="../profile/profile.php"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Profile</a>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-orange-50 hover:text-orange-700">Teacher
                    Dashboard</a>
                  <a href="../auth/process/logout.php"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-red-700">Logout</a>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow py-12 px-6">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Teacher Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your courses and view statistics</p>
      </div>

      <!-- Navigation -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="flex flex-wrap">
          <button data-section="newCourse"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-orange-600 border-b-2 border-orange-500 font-medium">
            <i class="fas fa-plus-circle"></i>
            <span>New Course</span>
          </button>
          <button data-section="manageCourses"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-book"></i>
            <span>Manage Courses</span>
          </button>
          <button data-section="statistics"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-chart-bar"></i>
            <span>Statistics</span>
          </button>
        </div>
      </div>

      <!-- Sections -->
      <div id="newCourse" class="contentSection flex bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="w-full text-center text-gray-500">
          <i class="fas fa-plus-circle text-4xl mb-4"></i>
          <p>New Course Section - Content Coming Soon</p>
        </div>
      </div>

      <div id="manageCourses" class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="w-full text-center text-gray-500">
          <i class="fas fa-book text-4xl mb-4"></i>
          <p>Manage Courses Section - Content Coming Soon</p>
        </div>
      </div>

      <div id="statistics" class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="w-full text-center text-gray-500">
          <i class="fas fa-chart-bar text-4xl mb-4"></i>
          <p>Statistics Section - Content Coming Soon</p>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div>
          <div class="text-2xl font-bold text-orange-500 mb-2">Youdemy</div>
          <p class="text-gray-400 text-sm">Share your knowledge with the world</p>
        </div>
        <div class="flex justify-center">
          <ul class="space-y-1 text-center">
            <li><a href="#" class="text-gray-400 hover:text-white transition text-sm">Home</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition text-sm">Courses</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition text-sm">Contact</a></li>
          </ul>
        </div>
        <div class="flex flex-col items-end">
          <h3 class="text-sm font-semibold mb-2">Follow Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-instagram"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-4 pt-4 text-center">
        <p class="text-gray-400 text-xs">&copy; 2024 Youdemy. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="../../js/menu.js"></script>
  <script src="../../js/teacherDashboard.js"></script>

</body>

</html>