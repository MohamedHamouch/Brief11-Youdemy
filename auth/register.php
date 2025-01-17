<?php
session_start();
if (isset($_SESSION['user'])) {
  header('Location: ../index.php');
  exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Register</title>
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
                <a href="../index.php"
                  class="text-gray-800 hover:text-orange-600 transition-colors duration-300">Home</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition-colors duration-300">Courses</a>
              </li>
              <li>
                <a href="login.php"
                  class="flex items-center space-x-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-full hover:bg-orange-100 transition-colors duration-300">
                  <i class="fas fa-sign-in-alt text-lg"></i>
                  <span>Sign In</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow flex items-center justify-center p-4">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-8">
          <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-user-plus text-3xl text-orange-500"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
            <p class="text-gray-600 mt-2">Already have an account?
              <a href="login.php" class="text-orange-500 hover:text-orange-600">Sign in here</a>
            </p>
          </div>

          <?php if (isset($_SESSION['registerError'])) { ?>
            <div class="bg-red-50 text-red-500 text-sm p-4 rounded-lg mb-6">
              <?= $_SESSION['registerError'] ?>
            </div>
            <?php unset($_SESSION['registerError']); ?>
          <?php } ?>

          <form action="process/registerProcess.php" method="POST">
            <div class="space-y-6">

              <div class="grid grid-cols-2 gap-4">
                <label class="relative cursor-pointer">
                  <input type="radio" name="role" value="student" class="peer sr-only" checked>
                  <div
                    class="w-full p-4 border rounded-lg peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-200 transition-colors text-center">
                    <i class="fas fa-user-graduate mb-2 text-xl text-orange-500"></i>
                    <h3 class="font-medium">Student</h3>
                    <p class="text-sm text-gray-500">Learn new skills</p>
                  </div>
                </label>
                <label class="relative cursor-pointer">
                  <input type="radio" name="role" value="teacher" class="peer sr-only">
                  <div
                    class="w-full p-4 border rounded-lg peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-200 transition-colors text-center">
                    <i class="fas fa-chalkboard-teacher mb-2 text-xl text-orange-500"></i>
                    <h3 class="font-medium">Teacher</h3>
                    <p class="text-sm text-gray-500">Share knowledge</p>
                  </div>
                </label>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                <input type="email" name="email" id="email" required
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  placeholder="Enter your email">
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First name</label>
                  <input type="text" name="first_name" id="first_name" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="First name">
                </div>
                <div>
                  <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last name</label>
                  <input type="text" name="last_name" id="last_name" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="Last name">
                </div>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  placeholder="Create a password">
              </div>

              <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                  password</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  placeholder="Confirm your password">
              </div>

              <button type="submit"
                class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
                Create Account
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-900 text-white">
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
</body>

</html>