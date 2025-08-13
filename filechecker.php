<?php
$filePath = "/Applications/MAMP/htdocs";

$folders = [];
$projects = [];

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
    echo htmlspecialchars($project) . "<br>";
}