<?php
session_start();
require_once 'config/database.php';
require_once 'classes/course.php';
require_once 'classes/videoCourse.php';
require_once 'classes/documentCourse.php';
require_once 'classes/student.php';
require_once 'classes/teacher.php';
require_once 'classes/admin.php';

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  $connected = true;
} else {
  $connected = false;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Home</title>
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
                <a href="#"
                  class="text-gray-800 font-medium hover:text-orange-600 transition-colors duration-300">Home</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Contact</a>
              </li>
              <?php
              if (!$connected) {
                echo '<li>
                <a href="pages/auth/login.php"
                  class="flex items-center space-x-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-full hover:bg-orange-100 transition-colors duration-300">
                  <i class="fas fa-sign-in-alt text-lg"></i>
                  <span>Sign In</span>
                </a>
              </li>';
              } else {
                ?>
                <li class="relative">
                  <button id="dropdownButton"
                    class="flex items-center space-x-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-full hover:bg-orange-100 transition-colors duration-300">
                    <i class="fas fa-user-circle text-lg"></i>
                    <span><?= $user ?></span>
                    <i class="fas fa-chevron-down text-sm"></i>
                  </button>
                  <div id="dropdownMenu"
                    class="hidden w-full absolute mt-2 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                    <a href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Profile</a>
                    <?php
                    if ($user instanceof Admin) {
                      echo '<a href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Admin Dashboard</a>';
                    } elseif ($user instanceof Teacher) {
                      echo '<a href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Teacher Dashboard</a>';
                    } elseif ($user instanceof Student) {
                      echo '<a href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">My Courses</a>';
                    }
                    ?>
                    <a href="pages/auth/process/logout.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-red-700">Logout</a>
                  </div>
                </li>
                <?php
              }
              ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <section class="pt-24 pb-12 bg-gradient-to-b from-orange-100 to-white">
    <div class="container mx-auto px-4 py-16 text-center">
      <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Welcome to Youdemy</h1>
      <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">Join our global community of learners and
        instructors.
        Whether you're here to master new skills or share your expertise, Youdemy is your platform for growth.
      </p>
      <a href="#"
        class="inline-block bg-orange-500 text-white px-8 py-3 rounded-full font-medium hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
        Start Today
      </a>
    </div>
  </section>

  <section class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Latest Courses</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <article
          class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden group">
          <div class="aspect-w-16 aspect-h-9 overflow-hidden">
            <img src="placeholder.jpg" alt="Course Title"
              class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
          </div>
          <div class="p-6">
            <p class="text-sm text-orange-500 font-medium mb-2">January 15, 2024</p>
            <h2 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
              Sample Course Title
            </h2>
            <p class="text-gray-600 mb-4 line-clamp-3">
              This is a sample Course description. The content would go here.
            </p>
            <a href="#"
              class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium group-hover:translate-x-1 transition-transform duration-200">
              Read More
              <i class="fas fa-arrow-right ml-2 text-sm"></i>
            </a>
          </div>
        </article>
        <!-- More Courses would go here -->
      </div>
      <div class="text-center mt-12">
        <a href="#"
          class="inline-block bg-gray-900 text-white px-8 py-3 rounded-full font-medium hover:bg-gray-800 transform hover:-translate-y-0.5 transition">
          View All Courses
        </a>
      </div>
    </div>
  </section>

  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div>
          <div class="text-2xl font-bold text-orange-500 mb-2">Youdemy</div>
          <p class="text-gray-400 text-sm">Share your stories with the world.</p>
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
  <script src="js/menu.js"></script>
</body>

</html>