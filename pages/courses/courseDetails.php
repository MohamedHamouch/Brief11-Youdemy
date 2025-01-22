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
  $courseNotFound = false;

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
  <header class="w-full z-50">
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
                    class="hidden w-full absolute mt-2 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
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

    <?php if (!$connected) { ?>
      <div class="min-h-[60vh] flex items-center justify-center p-4">
        <div class="text-center p-6">
          <div class="mb-4 text-orange-500">
            <i class="fas fa-user-lock text-5xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Access Restricted</h2>
          <p class="text-gray-600 mb-6">Log in to enjoy access to detailed course information.</p>
          <a href="../auth/login.php"
            class="inline-block bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
            Log In
          </a>
          <p class="mt-4 text-sm text-gray-600">
            Don't have an account?
            <a href="../auth/register.php" class="text-orange-500 hover:underline">Sign Up</a>
          </p>
        </div>
      </div>

    <?php } elseif ($user->isSuspended()) { ?>
      <div class="bg-gray-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6">
            <div class="text-center mb-6">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="fas fa-ban text-2xl text-red-500"></i>
              </div>
              <h2 class="text-2xl font-bold text-gray-900 mb-2">Account Suspended</h2>
              <p class="text-gray-600">Your account has been suspended due to a policy violation or pending
                investigation.
                As a result, you are unable to access or read courses.</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 mb-6">
              <div class="flex items-start">
                <div class="flex-shrink-0">
                  <i class="fas fa-info-circle text-red-500 mt-1"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">
                    Please contact our support team to resolve this issue. Include any relevant details
                    to expedite the
                    process.
                  </p>
                </div>
              </div>
            </div>
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-900">You may also:</h3>
              <ul class="space-y-3">
                <li class="flex items-start">
                  <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                  <span class="text-gray-600">Review our <a href="#" class="text-orange-500 underline">terms of
                      service</a></span>
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                  <span class="text-gray-600">Update your account information</span>
                </li>
              </ul>
            </div>

            <div class="mt-8">
              <a href="../contact/contact.php"
                class="block w-full bg-red-500 text-white text-center px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                Contact Support
              </a>
            </div>
          </div>
        </div>
      </div>

      <?php
    } elseif ($courseNotFound) { ?>

      <div class="min-h-[60vh] flex items-center justify-center px-4">
        <div class="text-center">
          <div class="mb-4 text-orange-500">
            <i class="fas fa-search text-5xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Course Not Found</h2>
          <p class="text-gray-600 mb-6">The course you're looking for doesn't exist or has been removed.</p>
          <a href="courses.php"
            class="inline-block bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition"">
            Browse Courses
          </a>
        </div>
      </div>
      <?php
    } else { ?>

    
     <!-- Course detail -->
        <div class=" container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

              <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                  <div class="relative">
                    <img src="../../uploads/covers/<?= htmlspecialchars($course->getImage()) ?>" alt="Course cover"
                      class="w-full h-[400px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                      <span class="bg-orange-500/90 text-white text-sm px-3 py-1 rounded-full">
                        <?= htmlspecialchars($categoryName) ?>
                      </span>
                      <h1 class="text-3xl font-bold mt-3 mb-2">
                        <?= htmlspecialchars($course->getTitle()) ?>
                      </h1>
                      <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                          <i class="fas fa-user-circle"></i>
                          <span><?= htmlspecialchars($teacherName) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                          <i class="fas fa-users"></i>
                          <span><?= $enrollmentCount ?> enrolled</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <i class="fas fa-calendar"></i>
                          <span><?= date('F j, Y', strtotime($course->getCreatedAt())) ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                  <div class="p-6">
                    <div class="flex flex-wrap gap-2 mb-6">
                      <?php foreach ($tags as $tag) { ?>
                        <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full">
                          <?= htmlspecialchars($tag['tag_name']) ?>
                        </span>
                      <?php } ?>
                    </div>

                    <div class="prose max-w-none">
                      <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-orange-500"></i>
                        Course Description
                      </h2>
                      <p class="text-gray-600">
                        <?= nl2br(htmlspecialchars($course->getDescription())) ?>
                      </p>
                    </div>

                    <div class="mt-8">
                      <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-book-open text-orange-500"></i>
                        Course Content
                      </h2>
                      <?php if ($course instanceof VideoCourse) { ?>
                        <div class="rounded-lg overflow-hidden bg-black">
                          <video controls class="w-full aspect-video">
                            <source src="../../uploads/videos/<?= htmlspecialchars($course->getVideoPath()) ?>"
                              type="video/mp4">
                            Your browser does not support the video tag.
                          </video>
                        </div>
                      <?php } else { ?>
                        <div class="bg-gray-50 rounded-lg p-6 prose max-w-none">
                          <?= nl2br(htmlspecialchars($course->getTextContent())) ?>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                  <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                      <i class="fas fa-comments text-orange-500"></i>
                      Course Comments
                    </h2>

                    <form class="mb-8">
                      <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Leave a
                          Comment</label>
                        <textarea id="comment" rows="4"
                          class="w-full px-4 py-3 rounded-lg resize-none border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                          placeholder="Share your thoughts about this course..."></textarea>
                      </div>
                      <button type="submit"
                        class="bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
                        Post Comment
                      </button>
                    </form>

                    <div class="COMMENT space-y-6">
                      <div class="flex gap-4">
                        <div class="flex-shrink-0">
                          <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-user text-orange-500"></i>
                          </div>
                        </div>
                        <div class="flex-grow">
                          <div class="flex items-center justify-between mb-1">
                            <h4 class="font-medium text-gray-900">John Doe</h4>
                            <span class="text-sm text-gray-500">2 days ago</span>
                          </div>
                          <p class="text-gray-600">Great course! The content is well-structured
                            and easy to follow. I
                            especially enjoyed the practical examples.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sidebar -->
              <div class="lg:col-span-1 z-10">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                  <?php if (!$connected) { ?>
                    <div class="text-center space-y-4">
                      <div class="text-4xl text-orange-500">
                        <i class="fas fa-lock"></i>
                      </div>
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Ready to Start Learning?
                        </h3>
                        <p class="text-gray-600 mb-4">Sign in to enroll in this course</p>
                        <a href="../auth/login.php"
                          class="inline-block bg-orange-500 text-white px-8 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
                          Sign In to Enroll
                        </a>
                      </div>
                    </div>
                  <?php } elseif ($user instanceof Student) { ?>
                    <?php if ($isEnrolled) { ?>
                      <div class="text-center space-y-4">
                        <div class="text-4xl text-green-500">
                          <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                          <h3 class="text-lg font-semibold text-gray-900 mb-2">You're Enrolled!</h3>
                          <p class="text-gray-600 text-sm">Continue learning from your courses page</p>
                          <a href="../student/myCourses.php"
                            class="inline-block mt-4 bg-green-500 text-white px-8 py-3 rounded-full hover:bg-green-600 transform hover:-translate-y-0.5 transition">
                            Go to My Courses
                          </a>
                        </div>
                      </div>
                    <?php } else { ?>
                      <div class="text-center space-y-4">
                        <div class="text-4xl text-orange-500">
                          <i class="fas fa-graduation-cap"></i>
                        </div>
                        <form action="../student/process/enrollCourse.php" method="POST">
                          <input type="hidden" name="courseId" value="<?= $course->getId() ?>">
                          <h3 class="text-lg font-semibold text-gray-900 mb-2">Ready to Start Learning?
                          </h3>
                          <p class="text-gray-600 mb-4">Enroll now to access the full course content</p>
                          <button type="submit"
                            class="bg-orange-500 text-white px-10 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition w-full">
                            Enroll Now
                          </button>
                        </form>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="text-center space-y-4">
                      <div class="text-4xl text-gray-500">
                        <i class="fas fa-info-circle"></i>
                      </div>
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Course Access</h3>
                        <p class="text-gray-600 text-sm">Only students can enroll in courses. You're
                          currently logged in as
                          a <?= $user instanceof Teacher ? 'teacher' : 'admin' ?>.</p>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
        </div>
      <?php } ?>
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
            <li><a href="../../index.php" class="text-gray-400 hover:text-white transition text-sm">Home</a>
            </li>
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

  <script src="../../js/menu.js"></script>
</body>

</html>