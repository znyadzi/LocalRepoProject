<?php
$filePath = "/Applications/MAMP/htdocs";

// Function definition goes here (OUTSIDE the loop)
function getLastModifiedTime($dir) {
    $lastModified = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $fileMTime = $file->getMTime();
            if ($fileMTime > $lastModified) {
                $lastModified = $fileMTime;
            }
        }
    }
    
    return $lastModified > 0 ? $lastModified : filemtime($dir);
}

$folders = [];
$projects = [];
$projectCreation = [];
$projectUpdate = [];

if ($handle = opendir($filePath)) {
    while (false !== ($entry = readdir($handle))) {
        // Skip current and parent directory references
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        $fullPath = $filePath . DIRECTORY_SEPARATOR . $entry;
        // Only add directories to the array
        if (is_dir($fullPath)) {
            $folders[] = $entry;
        }
    }
    closedir($handle);
} else {
    die("Unable to open directory");
}

foreach ($folders as $folder) {
    if ($folder != 'Landing_Page') {
        $projects[] = $folder;
    }
}
echo "Projects:<br>";

foreach ($projects as $project) {
    $projectPath = $filePath . DIRECTORY_SEPARATOR . $project;

    // Skip if not a directory or inaccessible
    if (!is_dir($projectPath)) {
        continue;
    }

    // Try to get creation time; on some systems filectime is inode change time,
    // so fallback to filemtime if necessary.
    $createdTs = @filectime($projectPath);
    if ($createdTs === false) {
        $createdTs = @filemtime($projectPath);
    }

    $updatedTs = getLastModifiedTime($projectPath);
    if ($updatedTs === false) {
        $updatedTs = $createdTs !== false ? $createdTs : time();
    }

    // Format dates as YYYY-MM-DD
    $createdDate = $createdTs !== false ? date('Y-m-d', $createdTs) : 'Unknown';
    $updatedDate = $updatedTs !== false ? date('Y-m-d', $updatedTs) : 'Unknown';

    echo htmlspecialchars($project) . "<br>";
    ?>
    <div class="project-card bg-gray-100 rounded-lg overflow-hidden cursor-pointer">
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
    </div>
    <?php
}
?>
