<?php
// filepath: /Applications/MAMP/htdocs/LocalRepoProject/filechecker.php
$filePath = "/Applications/MAMP/htdocs";

// Recursively finds the newest modification time (mtime) among all files in $dir.
// Returns the latest file mtime found, or falls back to the directory's mtime.
function getLastModifiedTime($dir) {
    $lastModified = 0;

    // Iterate recursively over files and folders, skipping . and ..
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    // Examine each file's mtime and keep the newest timestamp.
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $fileMTime = $file->getMTime();
            if ($fileMTime > $lastModified) {
                $lastModified = $fileMTime;
            }
        }
    }
    
    // If no files were found, fall back to the directory's mtime (may be false on failure).
    return $lastModified > 0 ? $lastModified : @filemtime($dir);
}

$folders = [];
$projects = [];
$projectCreation = [];
$projectUpdate = [];

// Open the top-level directory and collect its immediate child directories.
if ($handle = opendir($filePath)) {
    while (false !== ($entry = readdir($handle))) {
        // Skip current and parent directory entries
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        $fullPath = $filePath . DIRECTORY_SEPARATOR . $entry;
        // Only include entries that are directories
        if (is_dir($fullPath)) {
            $folders[] = $entry;
        }
    }
    closedir($handle);
} else {
    die("Unable to open directory");
}

// Convert folder list to projects, excluding a specific folder.
foreach ($folders as $folder) {
    if ($folder != 'Landing_Page') {
        $projects[] = $folder;
    }
}

// First pass: compute and store creation/update dates for each project.
// This keeps $projects, $projectCreation and $projectUpdate aligned by index.
foreach ($projects as $project) {
    $projectPath = $filePath . DIRECTORY_SEPARATOR . $project;

    // Skip entries that are no longer directories or inaccessible
    if (!is_dir($projectPath)) {
        // Store placeholders so indexes remain aligned
        $projectCreation[] = 'Unknown';
        $projectUpdate[] = 'Unknown';
        continue;
    }

    // Try to get creation timestamp. Note: on many Unix systems filectime is inode change time,
    // not the true "creation" time; fallback to filemtime when filectime isn't available.
    $createdTs = @filectime($projectPath);
    if ($createdTs === false) {
        $createdTs = @filemtime($projectPath);
    }

    // Determine the most recent modification time across all files in the project.
    $updatedTs = getLastModifiedTime($projectPath);
    if ($updatedTs === false) {
        // If that fails, fall back to created timestamp or current time.
        $updatedTs = ($createdTs !== false && $createdTs !== null) ? $createdTs : time();
    }

    // Format timestamps for display as YYYY-MM-DD, or use 'Unknown' if unavailable.
    $createdDate = ($createdTs !== false && $createdTs !== null) ? date('Y-m-d', $createdTs) : 'Unknown';
    $updatedDate = ($updatedTs !== false && $updatedTs !== null) ? date('Y-m-d', $updatedTs) : 'Unknown';

    // Append to arrays so they remain indexed in the same order as $projects.
    $projectCreation[] = $createdDate;
    $projectUpdate[] = $updatedDate;
}

// Second pass: render each project using values from the parallel arrays.
$projectCount = count($projects);
?>
