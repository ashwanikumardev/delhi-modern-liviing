<?php
// URL Helper Functions for Delhi Modern Living

function url($path = '') {
    $baseUrl = '/demo-pg-01-main';
    
    // Remove leading slash if present
    $path = ltrim($path, '/');
    
    // Return full URL
    return $baseUrl . ($path ? '/' . $path : '');
}

function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

function admin_url($path = '') {
    return url('admin/' . ltrim($path, '/'));
}

function auth_url($path = '') {
    return url('auth/' . ltrim($path, '/'));
}

function api_url($path = '') {
    return url('api/' . ltrim($path, '/'));
}

// Current URL helpers
function current_url() {
    return $_SERVER['REQUEST_URI'];
}

function is_current_url($path) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $checkPath = url($path);
    return $currentPath === $checkPath;
}

function redirect_to($path) {
    header('Location: ' . url($path));
    exit;
}
?>
