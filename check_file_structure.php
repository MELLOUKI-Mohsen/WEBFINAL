<?php
// Simple script to check if files exist
echo "<h1>File Structure Check</h1>";

$files_to_check = [
    'index.html',
    'login.php',
    'register.php',
    'dashboard.php',
    'php/login_process.php',
    'php/register_process.php',
    'php/config.php',
    'css/style.css',
    'css/auth.css',
    'css/dashboard.css',
    'create_user_tables.php'
];

echo "<ul>";
foreach ($files_to_check as $file) {
    if (file_exists("../" . $file)) {
        echo "<li style='color:green'>✓ File exists: $file</li>";
    } else {
        echo "<li style='color:red'>✗ File missing: $file</li>";
    }
}
echo "</ul>";

// Check the document root
echo "<h2>Server Information</h2>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current Script: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
?>