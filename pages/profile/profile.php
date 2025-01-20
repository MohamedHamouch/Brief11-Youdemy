<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';
require_once '../../classes/student.php';
require_once '../../classes/teacher.php';
require_once '../../classes/admin.php';

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
} else {
  header("Location: ../auth/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Profile</title>
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
                <a href="../courses/courses.php"
                  class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="../contact/contact.php"
                  class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Contact</a>
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
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-orange-50 hover:text-orange-700">Profile</a>
                  <?php if ($user instanceof Admin) { ?>
                    <a href="../admin/adminDashboard.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Admin
                      Dashboard</a>
                  <?php } elseif ($user instanceof Teacher) { ?>
                    <a href="../teacher/teacherDashboard.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Teacher
                      Dashboard</a>
                  <?php } elseif ($user instanceof Student) { ?>
                    <a href="../admin/adminDashboard.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">My
                      Courses</a>
                  <?php } ?>
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

  <!-- Profile -->
  <main class="py-12 px-4">

    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-orange-50 border-b border-gray-100">
          <h1 class="text-xl font-semibold text-gray-800">Profile Information</h1>
        </div>
        <div class="px-6 pt-6">
          <?php if (isset($_SESSION['updateProfileError'])) { ?>
            <div class="mb-6 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
              <div class="flex items-center space-x-2">
                <i class="fas fa-circle-exclamation"></i>
                <p><?= $_SESSION['updateProfileError'] ?></p>
              </div>
            </div>
            <?php unset($_SESSION['updateProfileError']); ?>
          <?php } ?>

          <?php if (isset($_SESSION['updateProfileSuccess'])) { ?>
            <div class="mb-6 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-600">
              <div class="flex items-center space-x-2">
                <i class="fas fa-circle-check"></i>
                <p><?= $_SESSION['updateProfileSuccess'] ?></p>
              </div>
            </div>
            <?php unset($_SESSION['updateProfileSuccess']); ?>
          <?php } ?>
        </div>

        <div class="p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
              <p class="text-gray-800 font-medium"><?= $user->getFirstName(); ?></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
              <p class="text-gray-800 font-medium"><?= $user->getLastName(); ?></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
              <p class="text-gray-800"><?= $user->getEmail(); ?></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
              <p class="text-gray-800 capitalize"><?= $user->getRole(); ?></p>
            </div>
          </div>

          <div class="pt-4 border-t border-gray-100">
            <button id="updateProfileBtn"
              class="bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
              Update Profile
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Update profile -->
  <section id="formPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
      <div class="rounded-t-xl bg-orange-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-800">Update Profile</h3>
        <button id="closeForm" class="text-gray-400 hover:text-gray-600 focus:outline-none">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>

      <div class="p-6">
        <form id="updateProfileForm" class="space-y-4" action="process/updateProfile.php" method="POST">
          <div>
            <label for="updateFirstName" class="block text-sm font-medium text-gray-700 mb-2">First
              Name</label>
            <input type="text" id="updateFirstName" name="firstName" required value="<?= $user->getFirstName(); ?>"
              class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
          </div>

          <div>
            <label for="updateLastName" class="block text-sm font-medium text-gray-700 mb-2">Last
              Name</label>
            <input type="text" id="updateLastName" name="lastName" required value="<?= $user->getLastName(); ?>"
              class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
          </div>

          <button type="submit"
            class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
            Save Changes
          </button>
        </form>
      </div>
    </div>
  </section>

  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div>
          <div class="text-2xl font-bold text-orange-500 mb-2">Youdemy</div>
          <p class="text-gray-400 text-sm">Learn and teach online with us</p>
        </div>
        <div class="flex justify-center">
          <ul class="space-y-1 text-center">
            <li><a href="../../index.php" class="text-gray-400 hover:text-white transition text-sm">Home</a></li>
            <li><a href="../courses/courses.php" class="text-gray-400 hover:text-white transition text-sm">Courses</a>
            </li>
            <li><a href="../contact/contact.php" class="text-gray-400 hover:text-white transition text-sm">Contact</a>
            </li>
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

  <script src="../../js/profile.js"></script>
  <script src="../../js/menu.js"></script>
</body>

</html>