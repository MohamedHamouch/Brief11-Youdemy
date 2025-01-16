<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Youdemy - Login</title>
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
                <a href="register.php"
                  class="flex items-center space-x-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-full hover:bg-orange-100 transition-colors duration-300">
                  <i class="fas fa-user-circle text-lg"></i>
                  <span>Register</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow flex items-center justify-center pt-24 pb-16">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-8">
          <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-user text-3xl text-orange-500"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Don't have an account?
              <a href="register.php" class="text-orange-500 hover:text-orange-600">Register here</a>
            </p>
          </div>

          <?php if (isset($_SESSION['login_error'])) { ?>
            <div class="bg-red-50 text-red-500 text-sm p-4 rounded-lg mb-6">
              <?= $_SESSION['login_error'] ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
          <?php } ?>

          <form action="handel_auth/login_process.php" method="POST">
            <div class="space-y-6">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                  address</label>
                <input type="email" name="email" id="email" required
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  placeholder="Enter your email">
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  placeholder="Enter your password">
              </div>

              <button type="submit"
                class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transform hover:-translate-y-0.5 transition">
                Sign In
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
          <img src="../assets/sphereblog.png" alt="Logo" class="w-20 mb-2">
          <p class="text-gray-400 text-sm">Share your stories with the world.</p>
        </div>
        <div class="flex justify-center">
          <ul class="space-y-1 text-center">
            <li><a href="../index.php" class="text-gray-400 hover:text-white transition text-sm">Home</a>
            </li>
            <li><a href="../articles/articles.php"
                class="text-gray-400 hover:text-white transition text-sm">Articles</a></li>
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
        <p class="text-gray-400 text-xs">&copy; 2024 BlogSphere. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>

</html>