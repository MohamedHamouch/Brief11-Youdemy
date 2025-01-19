<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/teacher.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';
require_once '../../classes/category.php';
require_once '../../classes/tag.php';


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
if (!$_SERVER['REQUEST_METHOD'] == 'POST') {

  $_SESSION['teacherActionError'] = 'Invalid request';
  header('Location: ../teacher/teacherDashboard.php');
  exit();
} else {
  if (!isset($_POST['courseId'])) {
    $_SESSION['teacherActionError'] = 'Course ID is required';
    header('Location: ../teacher/teacherDashboard.php');
    exit();
  }
  $courseId = $_POST['courseId'];
  $courseType = Course::checkCourseType($PDOConn, $courseId);
  if ($courseType == 'video') {
    $course = new VideoCourse($courseId);
  } else {
    $course = new DocumentCourse($courseId);
  }

  $course->loadCourse($PDOConn);
}
$tags = Tag::getAllTags($PDOConn);
$categories = Category::getAllCategories($PDOConn);


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
                  <a href="teacherDashboard.php"
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

  <main class="flex-grow py-12 px-10">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-semibold text-gray-800 my-6">Edit Course</h1>
      <div id="editCourse" class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <form class="w-full space-y-4" action="process/editCourse.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="courseId" value="<?= $course->getId() ?>">

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
              <label for="courseTitle" class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
              <input type="text" id="courseTitle" name="title" required
                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                value="<?= htmlspecialchars($course->getTitle()) ?>" placeholder="Enter course title">
            </div>

            <div class="md:col-span-2 grid grid-cols-2 gap-3">
              <div>
                <label for="courseType" class="block text-sm font-medium text-gray-700 mb-1">Content Type</label>
                <select id="courseType" name="type" required readonly
                  class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 cursor-not-allowed">
                  <option value="document" <?= $course->getType() === 'document' ? 'selected' : '' ?>>Document</option>
                  <option value="video" <?= $course->getType() === 'video' ? 'selected' : '' ?>>Video</option>
                </select>
              </div>

              <div>
                <label for="courseCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="courseCategory" name="category" required
                  class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>" <?= $course->getCategoryId() == $category['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($category['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-4">
              <div>
                <label for="courseDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="courseDescription" name="description" rows="3" required
                  class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
                  placeholder="Enter course description"><?= htmlspecialchars($course->getDescription()) ?></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Cover Image</label>
                <div class="w-full h-32 rounded-lg border border-gray-200 overflow-hidden">
                  <img src="../../uploads/covers/<?= htmlspecialchars($course->getImage()) ?>" alt="Current cover"
                    class="w-full h-full object-cover">
                </div>
              </div>

              <div>
                <label for="courseCover" class="block text-sm font-medium text-gray-700 mb-1">Update Cover Image</label>
                <label for="courseCover"
                  class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                  <div class="flex flex-col items-center justify-center pt-3 pb-4">
                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-500">
                      <span class="font-medium">Click to upload new image</span>
                    </p>
                    <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                  </div>
                  <input id="courseCover" name="image" type="file" class="hidden" accept="image/png, image/jpeg">
                </label>
              </div>

              <div>
                <label for="courseTags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                <select id="courseTags" name="tags[]" multiple required
                  class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                  <?php foreach ($tags as $tag): ?>
                    <option value="<?= htmlspecialchars($tag['id']) ?>" <?= in_array($tag['id'], $course->getTags()) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($tag['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl (Cmd on Mac) to select multiple tags</p>
              </div>
            </div>

            <div class="space-y-4">
              <?php if ($course->getType() === 'document'): ?>
                <div id="documentContent">
                  <label for="courseDocument" class="block text-sm font-medium text-gray-700 mb-1">Course Content</label>
                  <textarea id="courseDocument" name="text" rows="12"
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
                    placeholder="Enter your course content here"><?= htmlspecialchars($course->getTextContent()) ?></textarea>
                </div>
              <?php else: ?>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Current Video</label>
                  <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 break-all"><?= htmlspecialchars($course->getVideoPath()) ?></p>
                  </div>
                </div>
                <div id="videoContent">
                  <label for="courseVideo" class="block text-sm font-medium text-gray-700 mb-1">Update Course
                    Video</label>
                  <label for="courseVideo"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-3 pb-4">
                      <i class="fas fa-video text-2xl text-gray-400 mb-2"></i>
                      <p class="text-sm text-gray-500">
                        <span class="font-medium">Upload new video file</span>
                      </p>
                      <p class="text-xs text-gray-500">MP4, MKV or WebM (MAX. 100MB)</p>
                    </div>
                    <input id="courseVideo" name="video" type="file" class="hidden"
                      accept="video/mp4,video/webm,video/mkv">
                  </label>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end pt-2">
            <button type="submit"
              class="bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
              Update Course
            </button>
          </div>
        </form>
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

</body>

</html>