<?php
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "Parsed Path: " . $path . "\n";
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
echo "Dirname SCRIPT_NAME: " . $scriptName . "\n";

if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
    echo "Match found using strpos\n";
    $finalPath = substr($path, strlen($scriptName));
} else {
    echo "No match found using strpos\n";
    // Try decoded path
    $decodedPath = urldecode($path);
    echo "Decoded Path: " . $decodedPath . "\n";
    if ($scriptName !== '/' && strpos($decodedPath, $scriptName) === 0) {
        echo "Match found using decoded path!\n";
        $finalPath = substr($decodedPath, strlen($scriptName));
    } else {
        $finalPath = $path;
    }
}
echo "Final Path for routing: " . $finalPath . "\n";
echo "</pre>";
