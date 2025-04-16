<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">CARNEX Blog</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="blog.php" class="hover:underline">Blog</a></li>
                    <li><a href="login.php" class="hover:underline">Login</a></li>
                    <li><a href="browse_vehicles.php" class="hover:underline">Browse Vehicles</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-center mb-6">Our Latest Blog Posts</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-xl font-bold mb-2">Blog Post Title</h2>
                <p class="text-gray-600">A brief description of the blog post content goes here...</p>
                <a href="#" class="text-blue-500 hover:underline mt-2 block">Read More</a>
            </div>
            <!-- Repeat similar blocks for more blog posts -->
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p class="text-sm">&copy; 2025 CARNEX. All rights reserved.</p>
            <p class="text-sm">Designed and developed by CARNEX</p>
        </div>
    </footer>
</body>
</html>