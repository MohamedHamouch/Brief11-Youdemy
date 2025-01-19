<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';
require_once '../../classes/student.php';
require_once '../../classes/teacher.php';
require_once '../../classes/admin.php';

if (!isset($_GET['id'])) {
  header('Location: courses.php');
  exit();
}

$courseId = $_GET['id'];

if (!Course::checkCourseExsistance($PDOConn, $courseId)) {
  $courseNotFound = true;
} else {

  $courseType = Course::checkCourseType($PDOConn, $courseId);
  $course = ($courseType == 'video') ? new VideoCourse($courseId) : new DocumentCourse($courseId);
  $course->loadCourse($PDOConn);

  $teacherName = $course->getCourseTeacherName($PDOConn);
  $categoryName = $course->getCourseCategoryName($PDOConn);
  $tags = $course->getCourseTags($PDOConn);
  $enrollmentCount = $course->courseEnrollmentCount($PDOConn);
}

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  $connected = true;

  if ($user instanceof Student && !$courseNotFound) {
    $isEnrolled = $course->checkUserEnrollment($PDOConn, $user->getId());
  }
} else {
  $connected = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Course Details</title>
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
                <a href="courses.php"
                  class="text-gray-800 font-medium hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="../contact/contact.php"
                  class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Contact</a>
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
    <?php if (isset($courseNotFound) && $courseNotFound): ?>
      <!-- Course Not Found Section -->
      <div class="min-h-[60vh] flex items-center justify-center px-4">
        <div class="text-center">
          <div class="mb-4 text-orange-500">
            <i class="fas fa-search text-5xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Course Not Found</h2>
          <p class="text-gray-600 mb-6">The course you're looking for doesn't exist or has been removed.</p>
          <a href="courses.php"
            class="inline-block bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition">
            Browse Courses
          </a>
        </div>
      </div>
    <?php else: ?>
      <!-- Course Details Section -->
      <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Course Main Info -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
              <!-- Course Image -->
              <div class="relative h-[300px] md:h-[400px]">
                <img src="../../uploads/covers/<?= htmlspecialchars($course->getImage()) ?>" alt="Course cover"
                  class="w-full h-full object-cover">
              </div>

              <!-- Course Content -->
              <div class="p-6">
                <div class="mb-6">
                  <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                    <?= htmlspecialchars($course->getTitle()) ?>
                  </h1>
                  <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-orange-100 text-orange-600 text-sm px-3 py-1 rounded-full">
                      <?= htmlspecialchars($categoryName) ?>
                    </span>
                    <?php foreach ($tags as $tag): ?>
                      <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full">
                        <?= htmlspecialchars($tag['tag_name']) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                  <div class="flex items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-user-circle"></i>
                      <span><?= htmlspecialchars($teacherName) ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                      <i class="fas fa-calendar"></i>
                      <span><?= date('F j, Y', strtotime($course->getCreatedAt())) ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                      <i class="fas fa-users"></i>
                      <span><?= $enrollmentCount ?> enrolled</span>
                    </div>
                  </div>
                </div>

                <!-- Course Description -->
                <div class="prose max-w-none">
                  <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                  <p class="text-gray-600">
                    <?= nl2br(htmlspecialchars($course->getDescription())) ?>
                  </p>
                </div>

                <!-- Course Content -->
                <div class="mt-8">
                  <h2 class="text-xl font-semibold text-gray-900 mb-4">Course Content</h2>
                  <?php if ($course instanceof VideoCourse): ?>
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black">
                      <video controls class="w-full">
                        <source src="../../uploads/videos/<?= htmlspecialchars($course->getVideoPath()) ?>"
                          type="video/mp4">
                        Your browser does not support the video tag.
                      </video>
                    </div>
                  <?php else: ?>
                    <div class="bg-gray-50 rounded-lg p-6 prose max-w-none">
                      <?= nl2br(htmlspecialchars($course->getTextContent())) ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
              <?php if (!$connected): ?>
                <div class="text-center">
                  <p class="text-gray-600 mb-4">Sign in to enroll in this course</p>
                  <a href="../auth/login.php"
                    class="inline-block bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition-colors duration-300">
                    Sign In
                  </a>
                </div>
              <?php elseif ($user instanceof Student): ?>
                <?php if ($isEnrolled): ?>
                  <div class="text-center">
                    <div class="mb-4 text-green-500">
                      <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">You're Enrolled!</h3>
                    <p class="text-gray-600 text-sm">You have access to this course</p>
                  </div>
                <?php else: ?>
                  <form action="process/enrollCourse.php" method="POST" class="text-center">
                    <input type="hidden" name="courseId" value="<?= $course->getId() ?>">
                    <button type="submit"
                      class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition-colors duration-300">
                      Enroll Now
                    </button>
                  </form>
                <?php endif; ?>
              <?php else: ?>
                <div class="text-center">
                  <div class="mb-4 text-gray-500">
                    <i class="fas fa-info-circle text-4xl"></i>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Course Access</h3>
                  <p class="text-gray-600 text-sm">Only students can enroll in courses. You're currently logged in as a
                    <?= $user instanceof Teacher ? 'teacher' : 'admin' ?>.
                  </p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
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
</body>

</html>