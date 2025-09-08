<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Local Projects</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="LocalRepoProject/style.css">
</head>
<body class="bg-white min-h-screen text-gray-900">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <header class="mb-8 border-b border-gray-200 pb-6">
        <div class="flex flex-col items-center">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold mb-2" style="font-size: 28px;">My Local Projects</h1>
                <p class="text-base text-gray-600 max-w-2xl mx-auto" style="font-size: 16px;">
                    A collection of my development projects showcasing various technologies and creative solutions
                </p>
            </div>
            
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ri-search-line text-gray-400"></i>
                </div>
                <input type="text" id="search" placeholder="Search projects..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
            </div>
        </div>
        </header>
    </div>
    <div id="project-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sample Project Cards -->
        <?php
        // Include the file that checks projects and gathers their data
        include 'filechecker.php';
        for ($i = 0; $i < $projectCount; $i++) {
            $project = $projects[$i];
            // Safely read from the parallel arrays with fallbacks.
            $createdDate = isset($projectCreation[$i]) ? $projectCreation[$i] : 'Unknown';
            $updatedDate = isset($projectUpdate[$i]) ? $projectUpdate[$i] : 'Unknown';
            ?>
            <div class="project-card bg-gray-100 rounded-lg overflow-hidden cursor-pointer" data-project="<?php echo htmlspecialchars($project, ENT_QUOTES); ?>">
                <div class="w-full aspect-[16/9] bg-blue-500"></div>
                <div class="p-4">
                    <h2 class="text-lg font-semibold mb-2" style="font-size: 18px;"><?php echo htmlspecialchars($project); ?></h2>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-2">A web application for task management with real-time collaboration features.</p>
                    <div class="flex space-x-2 mb-2">
                        <i class="ri-reactjs-line text-xl text-blue-500"></i>
                        <i class="ri-nodejs-line text-xl text-green-500"></i>
                        <i class="ri-database-line text-xl text-purple-500"></i>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Created: <?php echo htmlspecialchars($createdDate); ?></span>
                        <span>Updated: <?php echo htmlspecialchars($updatedDate); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <!-- No Results Message -->
        <div id="no-results" class="text-center text-gray-600 py-8 col-span-full">
            <p class="text-lg mb-2">No results found.</p>
            <p>Try different search terms.</p>
        </div>
    </div>

    <button id="back-to-top" class="fixed bottom-4 right-4 bg-gray-800 text-white p-3 rounded-full opacity-50 hover:opacity-100 transition-opacity">
        <i class="ri-arrow-up-line"></i>
    </button>
    <script src="LocalRepoProject/script.js"></script>
<script>
/* Attach click listeners to project cards and redirect to current URL + /<project> */

// Find all tiles that have a data-project attribute
document.querySelectorAll('.project-card[data-project]').forEach(function(card) {
  // When a tile is clicked...
  card.addEventListener('click', function(event) {
    // Read the raw project name from the data attribute
    var projectName = card.getAttribute('data-project');
    if (!projectName) return; // nothing to do if missing

    // Build a URL object from the current location (preserves scheme, host, port)
    var url = new URL(window.location.href);

    // Normalize current path by removing any trailing slashes
    var currentPath = url.pathname.replace(/\/+$/, '');

    // Encode the project name safely for a path segment
    var encoded = encodeURIComponent(projectName);

    // Prevent redirect loop: if the path already ends with the project segment, do nothing
    if (currentPath.endsWith('/' + encoded) || currentPath.endsWith('/' + projectName)) {
      return;
    }

    // Append the encoded project name as a new path segment
    url.pathname = currentPath + '/' + encoded;

    // Perform the redirect
    window.location.href = url.toString();
  });
});
</script>
</body>
</html>