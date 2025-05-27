<?php
echo "<h1>File Path Check</h1>";

// Get the current directory
$current_dir = __DIR__;
echo "<p>Current directory: " . $current_dir . "</p>";

// List all files in the current directory
echo "<h2>Files in current directory:</h2>";
echo "<ul>";
$files = scandir($current_dir);
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        echo "<li>" . $file . " (Type: " . (is_dir($current_dir . '/' . $file) ? "Directory" : "File") . ")</li>";
    }
}
echo "</ul>";

// Check for specific files
$specific_files = [
    'register.php',
    'login.php',
    'index.html',
    'create_user_tables.php'
];

echo "<h2>Checking for specific files:</h2>";
echo "<ul>";
foreach ($specific_files as $file) {
    if (file_exists($current_dir . '/' . $file)) {
        echo "<li style='color:green'>✓ File exists: " . $file . "</li>";
    } else {
        echo "<li style='color:red'>✗ File missing: " . $file . "</li>";
    }
}
echo "</ul>";
?>