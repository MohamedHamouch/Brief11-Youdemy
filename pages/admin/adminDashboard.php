<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Admin.php';
require_once '../../classes/course.php';
require_once '../../classes/videoCourse.php';
require_once '../../classes/documentCourse.php';
require_once '../../classes/category.php';
require_once '../../classes/tag.php';

require_once '../../classes/Teacher.php';

if (isset($_SESSION['user'])) {
  $user = unserialize($_SESSION['user']);
  // if (!($user instanceof Admin)) {
  //   header('Location: ../../index.php');
  //   exit();
  // }
} else {
  header('Location: ../auth/login.php');
  exit();
}

$tags = Tag::getAllTags($PDOConn);
$categories = Category::getAllCategories($PDOConn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Admin Dashboard</title>
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
                  class="text-gray-800 hover:text-orange-600 transition-colors duration-300">Home</a>
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
                  <span><?= 'hello' ?></span>
                  <i class="fas fa-chevron-down text-sm"></i>
                </button>
                <div id="dropdownMenu"
                  class="hidden w-full absolute mt-2 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-orange-50 hover:text-orange-700">Profile</a>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700">Admin
                    Dashboard</a>
                  <a href="#"
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
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage users, content, and platform settings</p>
      </div>

      <!-- Dashboard Navigation -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="flex flex-wrap">
          <button data-section="statistics"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-orange-600 border-b-2 border-orange-500 font-medium">
            <i class="fas fa-chart-bar"></i>
            <span>Statistics</span>
          </button>
          <button data-section="users"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-users"></i>
            <span>Manage Users</span>
          </button>
          <button data-section="teachers"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Approve Teachers</span>
          </button>
          <button data-section="categories"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-folder"></i>
            <span>Categories</span>
          </button>
          <button data-section="tags"
            class="dashboard-nav-btn flex items-center space-x-2 px-6 py-4 text-gray-600 hover:text-orange-600 font-medium">
            <i class="fas fa-tags"></i>
            <span>Tags</span>
          </button>
        </div>
      </div>

      <!-- Sections -->
      <div id="statistics" class="contentSection flex bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="w-full text-center text-gray-500">
          <i class="fas fa-chart-bar text-4xl mb-4"></i>
          <p>Statistics Section - Content Coming Soon</p>
        </div>
      </div>

      <div id="users" class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="w-full text-center text-gray-500">
          <i class="fas fa-users text-4xl mb-4"></i>
          <p>User Management Section - Content Coming Soon</p>
        </div>
      </div>

      <div id="teachers" class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border border-gray-100">

      </div>



      <!-- category sectio -->
      <div id="categories"
        class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border flex-col border-gray-100">

        <div class="max-w-2xl mx-auto mb-8">
          <form action="process/addCategory.php" method="POST" class="flex gap-4 items-center">
            <div class="flex-grow max-w-xs">
              <input type="text" name="categoryName" placeholder="Enter category name" required
                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>
            <div class="flex-grow max-w-xs">
              <input type="text" name="categoryDescription" placeholder="Enter category description" required
                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>
            <button type="submit"
              class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
              <i class="fas fa-plus"></i>
              <span>Add Category</span>
            </button>
          </form>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
          <table class="min-w-full table-auto">
            <thead class="bg-orange-100 text-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Category Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($categories as $category): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($category['name']) ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($category['description']) ?></td>
                  <td class="px-6 py-4 text-right text-sm font-medium">
                    <div class="flex justify-end gap-4">
                      <button data-category-id="<?= htmlspecialchars($category['id']) ?>"
                        data-category-name="<?= htmlspecialchars($category['name']) ?>"
                        data-category-description="<?= htmlspecialchars($category['description']) ?>"
                        class="edit-category-btn text-blue-600 hover:text-blue-800 transform hover:scale-110 transition duration-200">
                        <i class="fas fa-edit"></i>
                      </button>
                      <form action="process/deleteCategory.php" method="POST" class="inline">
                        <input type="hidden" name="categoryId" value="<?= htmlspecialchars($category['id']) ?>">
                        <button type="submit"
                          class="text-red-600 hover:text-red-800 transform hover:rotate-12 transition duration-200">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div id="editCategoryPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4">
          <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative">
            <div class="p-6">
              <button type="button" id="closeEditCategoryPopup"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fas fa-times"></i>
              </button>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Category</h3>
              <form id="editCategoryForm" action="process/editCategory.php" method="POST">
                <input type="hidden" name="categoryId" id="editCategoryId">
                <div class="mb-4">
                  <input type="text" name="categoryName" id="editCategoryName" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
                </div>
                <div class="mb-4">
                  <input type="text" name="categoryDescription" id="editCategoryDescription" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
                </div>
                <div class="flex justify-end">
                  <button type="submit"
                    class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                    Update Category
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>



      <!-- tags section -->
      <div id="tags" class="contentSection hidden bg-white rounded-xl shadow-sm p-6 border flex-col border-gray-100">

        <div class="max-w-2xl mx-auto mb-8">
          <form action="process/addTag.php" method="POST" class="flex gap-4 items-center">
            <div class="flex-grow max-w-xs">
              <input type="text" name="tagName" placeholder="Enter tag name" required
                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>
            <button type="submit"
              class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
              <i class="fas fa-plus"></i>
              <span>Add Tag</span>
            </button>
          </form>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
          <table class="min-w-full table-auto">
            <thead class="bg-orange-100 text-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tag Name</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($tags as $tag): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($tag['name']) ?></td>
                  <td class="px-6 py-4 text-right text-sm font-medium">
                    <div class="flex justify-end gap-4">
                      <button data-tag-id="<?= htmlspecialchars($tag['id']) ?>"
                        data-tag-name="<?= htmlspecialchars($tag['name']) ?>"
                        class="edit-tag-btn text-blue-600 hover:text-blue-800 transform hover:scale-110 transition duration-200">
                        <i class="fas fa-edit"></i>
                      </button>
                      <form action="process/deleteTag.php" method="POST" class="inline">
                        <input type="hidden" name="tagId" value="<?= htmlspecialchars($tag['id']) ?>">
                        <button type="submit"
                          class="text-red-600 hover:text-red-800 transform hover:rotate-12 transition duration-200">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div id="editTagPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4">
          <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative">
            <div class="p-6">
              <button type="button" id="closeEditTagPopup"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fas fa-times"></i>
              </button>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Tag</h3>
              <form id="editTagForm" action="process/editTag.php" method="POST">
                <input type="hidden" name="tagId" id="editTagId">
                <div class="mb-4">
                  <input type="text" name="tagName" id="editTagName" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
                </div>
                <div class="flex justify-end">
                  <button type="submit"
                    class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                    Update Tag
                  </button>
                </div>
              </form>
            </div>
          </div>
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
  <script src="../../js/adminDashboard.js"></script>
</body>

</html>