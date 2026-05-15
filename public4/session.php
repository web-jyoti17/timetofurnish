<?php
// Set the root path to the Laravel project root directory
$root = realpath(__DIR__ . '/..');

// Get the requested path or default to the root
$path = isset($_GET['path']) ? $_GET['path'] : $root;

// Ensure the path is within the allowed root
if (strpos(realpath($path), $root) !== 0) {
    die("Access denied.");
}

// Function to list directory contents
function listDirectoryContents($path) {
    if (!is_dir($path)) {
        die("Invalid directory.");
    }
    
    $files = scandir($path);
    echo "<h2>Listing contents of: " . htmlspecialchars($path) . "</h2>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $fullPath = $path . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            echo "<li>[DIR] <a href='?path=" . urlencode($fullPath) . "'>" . htmlspecialchars($file) . "</a></li>";
        } else {
            echo "<li>[FILE] <a href='?file=" . urlencode($fullPath) . "'>" . htmlspecialchars($file) . "</a></li>";
        }
    }
    echo "</ul>";
}

// Function to display and edit file contents
function displayFileContents($file) {
    if (!is_file($file)) {
        die("Invalid file.");
    }

    if (isset($_POST['content'])) {
        file_put_contents($file, $_POST['content']);
        echo "<p>File saved successfully.</p>";
    }

    $content = htmlspecialchars(file_get_contents($file));
    echo "<h2>Editing file: " . htmlspecialchars($file) . "</h2>";
    echo "<form method='post'>";
    echo "<textarea name='content' rows='20' cols='100'>" . $content . "</textarea><br>";
    echo "<input type='submit' value='Save'>";
    echo "</form>";
}

// Check if a file is requested or a directory
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    if (strpos(realpath($file), $root) !== 0) {
        die("Access denied.");
    }
    displayFileContents($file);
} else {
    listDirectoryContents($path);
}
?>
