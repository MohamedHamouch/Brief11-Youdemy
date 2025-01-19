<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';
require_once '../../classes/student.php';
require_once '../../classes/teacher.php';
require_once '../../classes/admin.php';
require_once '../../classes/category.php';


if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  $connected = true;
} else {
  $connected = false;
}

$categories = Category::getAllCategories($PDOConn);
if (isset($_GET['search']) || isset($_GET['category'])) {

  $search = $_GET['search'] ?? '';
  $category = $_GET['category'] ?? '';
  $courses = Course::filterCourses($PDOConn, $search, $category);
} else {
  $courses = Course::getAllCourses($PDOConn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Courses</title>
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
                <a href="#"
                  class="text-gray-800 font-medium hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Contact</a>
              </li>
              <?php
              if (!$connected) {
                echo '<li>
                <a href="../auth/login.php"
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
                    <a href="../profile/profile.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Profile</a>
                    <?php
                    if ($user instanceof Admin) {
                      echo '<a href="../admin/adminDashboard.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Admin Dashboard</a>';
                    } elseif ($user instanceof Teacher) {
                      echo '<a href="../teacher/teacherDashboard.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Teacher Dashboard</a>';
                    } elseif ($user instanceof Student) {
                      echo '<a href="../student/myCourses.php"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">My Courses</a>';
                    }
                    ?>
                    <a href="../auth/process/logout.php"
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


  <main class="flex-grow">

    <section class="bg-gradient-to-b from-orange-100 to-white pt-24 pb-12">
      <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-8">Discover Courses</h1>
        <div class="max-w-2xl mx-auto">

          <!-- filtrage form -->
          <form method="GET" id="filterForm" class="space-y-4">
            <div class="relative">
              <input type="text" name="search" placeholder="Search for courses..."
                class="w-full px-6 py-4 pr-12 rounded-full border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all shadow-sm">
              <button type="submit"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors">
                <i class="fas fa-search text-lg"></i>
              </button>
            </div>

            <div class="relative flex items-center justify-center">
              <select id="categorySelect" name="category"
                class="flex-grow max-w-xs px-6 py-3 rounded-full border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all shadow-sm text-gray-600">
                <option value="">All Categories</option>
                <?php
                foreach ($categories as $category) {
                  ?>
                  <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?>
                  </option>
                  <?php
                }
                ?>
              </select>
            </div>
          </form>
        </div>
      </div>
    </section>


    <!-- Courses -->
    <section class="py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <?php
          foreach ($courses as $course) {
            ?>
            <div
              class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
              <div class="relative h-48 bg-gray-100">
                <img src="../../uploads/covers/<?= htmlspecialchars($course['image']) ?>" alt="Course thumbnail"
                  class="w-full h-full object-cover">
                <span class="absolute top-4 right-4 bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">
                  <?= htmlspecialchars($course['category_name']) ?>
                </span>
                </span>
              </div>
              <div class="p-6">
                <a href="courseDetails.php?id=<?= $course['id'] ?>" class="block">
                  <h3
                    class="text-lg font-semibold text-gray-900 mb-2 hover:text-orange-600 transition-colors duration-300 break-words line-clamp-2">
                    <?= htmlspecialchars($course['title']) ?>
                  </h3>
                </a>
                <p class="text-sm text-gray-600 mb-4 break-words line-clamp-2">
                  <?= htmlspecialchars($course['description']) ?>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                      <i class="fas fa-user text-orange-500"></i>
                    </div>
                    <p class="text-sm text-gray-600">
                      <?= htmlspecialchars("{$course['first_name']} {$course['last_name']}") ?>
                    </p>
                  </div>
                  <div class="text-sm text-gray-500">
                    <?= date('F j, Y', strtotime($course['created_at'])) ?>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
          <nav class="flex items-center space-x-2">
            <button
              class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-orange-50 hover:text-orange-600 transition-colors">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button
              class="px-4 py-2 rounded-lg border border-orange-500 bg-orange-50 text-orange-600 font-medium">1</button>
            <button
              class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-500 transition-colors">2</button>
            <button
              class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-500 transition-colors">3</button>
            <button
              class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-orange-50 hover:text-orange-600 transition-colors">
              <i class="fas fa-chevron-right"></i>
            </button>
          </nav>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div>
          <div class="text-2xl font-bold text-orange-500 mb-2">Youdemy</div>
          <p class="text-gray-400 text-sm">Learn and teach online with us</p>
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
  <script src="../../js/courses.js"></script>


</body>

</html>