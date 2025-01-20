<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/student.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Student)) {
    header('Location: ../../index.php');
    exit();
  }
} else {
  header('Location: ../auth/login.php');
  exit();
}

$enrolledCourses = $user->getEnrolledCourses($PDOConn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - My Courses</title>
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
                  <a href="../profile/profile.php"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Profile</a>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-orange-50 hover:text-orange-700">My
                    Courses</a>
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

  <main class="flex-1 mx-auto px-6 pt-16 max-w-6xl">
    <?php if (!$user->isSuspended()) { ?>
      <!-- error/success msg -->
      <?php if (isset($_SESSION['enrollError'])) { ?>
        <div class="message bg-red-50 border border-red-300 p-4 rounded-lg mb-6 flex justify-between items-center">
          <div class="flex items-center">
            <i class="fas fa-times-circle text-red-500 text-xl mr-3"></i>
            <p class="text-red-700 font-medium"><?= htmlspecialchars($_SESSION['enrollError']) ?></p>
          </div>
          <button class="dismiss-button text-red-600 hover:underline focus:outline-none">
            Dismiss
          </button>
        </div>
        <?php unset($_SESSION['enrollError']); ?>
      <?php } ?>

      <?php if (isset($_SESSION['enrollSuccess'])) { ?>
        <div class="message bg-green-50 border border-green-300 p-4 rounded-lg mb-6 flex justify-between items-center">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <p class="text-green-700 font-medium"><?= htmlspecialchars($_SESSION['enrollSuccess']) ?></p>
          </div>
          <button class="dismiss-button text-green-600 hover:underline focus:outline-none">
            Dismiss
          </button>
        </div>
        <?php unset($_SESSION['enrollSuccess']); ?>
      <?php } ?>


      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 pb-8 bg-orange-50 border-b border-gray-100">
          <h1 class="text-xl font-semibold text-gray-800 pt-4">My Enrolled Courses</h1>
          <p class="text-gray-600 text-sm mt-1">
            Here's a list of courses you're currently enrolled in. Stay on track and keep learning!
          </p>
        </div>

        <div class="p-6">
          <?php if (empty($enrolledCourses)) { ?>
            <div class="text-center py-8">
              <i class="fas fa-book-open text-gray-400 text-4xl mb-4"></i>
              <p class="text-gray-500">You haven't enrolled in any courses yet.</p>
              <a href="../courses/courses.php"
                class="inline-block mt-4 bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition"">
              Browse Courses
            </a>
          </div>
        <?php } else { ?>
          <div class=" mb-6">
                <p class="text-gray-700 text-sm flex items-center">
                  <i class="fas fa-layer-group text-orange-500 mr-2"></i>
                  Total Courses Enrolled : <span class="font-semibold"><?= count($enrolledCourses) ?></span>
                </p>
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-full w-full table-auto">
                <thead class="bg-orange-50 text-gray-800">
                  <tr class="">
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Enrolled On</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php foreach ($enrolledCourses as $course): ?>
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="../courses/courseDetails.php?id=<?= urlencode($course['id']) ?>"
                          class="font-medium text-gray-800 hover:text-orange-600">
                          <?= htmlspecialchars($course['title']) ?>
                        </a>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        <?= htmlspecialchars($course['teacher_name']) ?>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        <?= htmlspecialchars($course['category_name']) ?>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        <?= htmlspecialchars(ucfirst($course['type'])) ?>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        <?= date('F j, Y', strtotime($course['enrollment_date'])) ?>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <form action="process/cancelEnrollment.php" method="POST"
                          onsubmit="return confirm('Are you sure you want to cancel your enrollment in this course?')"
                          class="inline">
                          <input type="hidden" name="courseId" value="<?= htmlspecialchars($course['id']) ?>">
                          <button type="submit"
                            class="group relative text-sm text-gray-600 bg-gray-100 hover:bg-red-50 hover:text-red-600 px-3 py-2 rounded-full transition duration-200">

                            <i class="fas fa-times-circle text-lg"></i>
                            <!-- <span
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 translate-y-2 opacity-0 group-hover:opacity-100 bg-gray-700 text-white text-xs rounded px-2 py-1 transition-opacity duration-200">
                            Cancel Enrollment
                          </span> -->
                          </button>
                        </form>
                      </td>

                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php } ?>
        </div>

      </div>
    <?php } else { ?>
      <div class="bg-gray-50 flex items-center justify-center pb-10">
        <div class="max-w-md w-full bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6">
            <div class="text-center mb-6">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="fas fa-ban text-2xl text-red-500"></i>
              </div>
              <h2 class="text-2xl font-bold text-gray-900 mb-2">Account Suspended</h2>
              <p class="text-gray-600">Your account has been suspended due to a policy violation or pending
                investigation.</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 mb-6">
              <div class="flex items-start">
                <div class="flex-shrink-0">
                  <i class="fas fa-info-circle text-red-500 mt-1"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">
                    Please contact our support team to resolve this issue. Include any relevant details to expedite the
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

  <script src="../../js/menu.js"></script>
  <script src="../../js/messages.js"></script>

</body>

</html>