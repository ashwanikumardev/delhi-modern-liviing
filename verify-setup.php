<?php
/**
 * Setup Verification Script
 * This script verifies that all routes and configurations are working correctly
 */

// Check if running from command line or browser
$isCLI = php_sapi_name() === 'cli';

if (!$isCLI) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Setup Verification - Delhi Modern Living</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
        h1 { color: #2563eb; }
        .success { color: #059669; background: #d1fae5; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #d97706; background: #fef3c7; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #2563eb; background: #dbeafe; padding: 10px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2563eb; color: white; }
        .check { color: #059669; font-weight: bold; }
        .cross { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>';
}

echo $isCLI ? "\n=== Delhi Modern Living - Setup Verification ===\n\n" : "<h1>Delhi Modern Living - Setup Verification</h1>";

$checks = [];
$errors = [];
$warnings = [];

// 1. Check PHP version
$phpVersion = phpversion();
$checks['PHP Version'] = $phpVersion;
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo $isCLI ? "✓ PHP Version: $phpVersion\n" : "<div class='success'>✓ PHP Version: $phpVersion</div>";
} else {
    $errors[] = "PHP version 7.4 or higher is required. Current: $phpVersion";
    echo $isCLI ? "✗ PHP Version: $phpVersion (7.4+ required)\n" : "<div class='error'>✗ PHP Version: $phpVersion (7.4+ required)</div>";
}

// 2. Check required PHP extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'session'];
echo $isCLI ? "\nChecking PHP Extensions:\n" : "<h2>PHP Extensions</h2>";

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo $isCLI ? "  ✓ $ext\n" : "<div class='success'>✓ $ext</div>";
    } else {
        $errors[] = "Required PHP extension missing: $ext";
        echo $isCLI ? "  ✗ $ext (missing)\n" : "<div class='error'>✗ $ext (missing)</div>";
    }
}

// 3. Check configuration files
echo $isCLI ? "\nChecking Configuration Files:\n" : "<h2>Configuration Files</h2>";

$configFiles = [
    'config/config.php',
    'config/database.php',
    'helpers/url_helper.php',
    'core/Router.php',
    'core/Controller.php',
    'core/Model.php',
    '.htaccess'
];

foreach ($configFiles as $file) {
    if (file_exists($file)) {
        echo $isCLI ? "  ✓ $file\n" : "<div class='success'>✓ $file</div>";
    } else {
        $errors[] = "Missing file: $file";
        echo $isCLI ? "  ✗ $file (missing)\n" : "<div class='error'>✗ $file (missing)</div>";
    }
}

// 4. Check URL configuration
echo $isCLI ? "\nChecking URL Configuration:\n" : "<h2>URL Configuration</h2>";

require_once 'config/config.php';
require_once 'helpers/url_helper.php';

$expectedBaseUrl = '/demo-pg-01-main';
$actualBaseUrl = url('');
$siteUrl = SITE_URL;

if ($actualBaseUrl === $expectedBaseUrl) {
    echo $isCLI ? "  ✓ Base URL: $actualBaseUrl\n" : "<div class='success'>✓ Base URL: $actualBaseUrl</div>";
} else {
    $warnings[] = "Base URL might be incorrect. Expected: $expectedBaseUrl, Got: $actualBaseUrl";
    echo $isCLI ? "  ⚠ Base URL: $actualBaseUrl (expected: $expectedBaseUrl)\n" : "<div class='warning'>⚠ Base URL: $actualBaseUrl (expected: $expectedBaseUrl)</div>";
}

echo $isCLI ? "  ✓ Site URL: $siteUrl\n" : "<div class='success'>✓ Site URL: $siteUrl</div>";

// 5. Check controllers
echo $isCLI ? "\nChecking Controllers:\n" : "<h2>Controllers</h2>";

$controllers = [
    'HomeController' => ['index', 'about', 'contact'],
    'RoomController' => ['index', 'show'],
    'AuthController' => ['signup', 'login', 'logout', 'forgotPassword', 'resetPassword'],
    'CartController' => ['index', 'add', 'remove'],
    'CheckoutController' => ['index'],
    'OrderController' => ['index', 'download'],
    'ProfileController' => ['index'],
    'AdminController' => ['dashboard'],
    'AdminAuthController' => ['login', 'logout']
];

foreach ($controllers as $controller => $methods) {
    $controllerFile = "controllers/$controller.php";
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        if (class_exists($controller)) {
            $allMethodsExist = true;
            foreach ($methods as $method) {
                if (!method_exists($controller, $method)) {
                    $allMethodsExist = false;
                    $errors[] = "Method $controller::$method() not found";
                }
            }
            if ($allMethodsExist) {
                echo $isCLI ? "  ✓ $controller (all methods present)\n" : "<div class='success'>✓ $controller (all methods present)</div>";
            } else {
                echo $isCLI ? "  ✗ $controller (missing methods)\n" : "<div class='error'>✗ $controller (missing methods)</div>";
            }
        } else {
            $errors[] = "Controller class $controller not found";
            echo $isCLI ? "  ✗ $controller (class not found)\n" : "<div class='error'>✗ $controller (class not found)</div>";
        }
    } else {
        $errors[] = "Controller file not found: $controllerFile";
        echo $isCLI ? "  ✗ $controller (file not found)\n" : "<div class='error'>✗ $controller (file not found)</div>";
    }
}

// 6. Check writable directories
echo $isCLI ? "\nChecking Directory Permissions:\n" : "<h2>Directory Permissions</h2>";

$writableDirs = ['uploads'];
foreach ($writableDirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo $isCLI ? "  ✓ $dir (writable)\n" : "<div class='success'>✓ $dir (writable)</div>";
    } else {
        $warnings[] = "Directory $dir is not writable";
        echo $isCLI ? "  ⚠ $dir (not writable)\n" : "<div class='warning'>⚠ $dir (not writable)</div>";
    }
}

// 7. Test Routes
echo $isCLI ? "\nAvailable Routes:\n" : "<h2>Available Routes</h2><table><tr><th>Route</th><th>URL</th></tr>";

$routes = [
    'Home' => url('/'),
    'About' => url('about'),
    'Contact' => url('contact'),
    'Rooms' => url('rooms'),
    'Cart' => url('cart'),
    'Login' => url('auth/login'),
    'Signup' => url('auth/signup'),
    'Admin Login' => url('admin/login'),
    'Admin Dashboard' => url('admin/dashboard')
];

foreach ($routes as $name => $routeUrl) {
    echo $isCLI ? "  • $name: $routeUrl\n" : "<tr><td>$name</td><td><a href='$routeUrl' target='_blank'>$routeUrl</a></td></tr>";
}

if (!$isCLI) {
    echo "</table>";
}

// Summary
echo $isCLI ? "\n=== Summary ===\n" : "<h2>Summary</h2>";

if (count($errors) === 0 && count($warnings) === 0) {
    echo $isCLI ? "✓ All checks passed! Your application is ready.\n" : "<div class='success'><strong>✓ All checks passed!</strong> Your application is ready to use.</div>";
} else {
    if (count($errors) > 0) {
        echo $isCLI ? "\nErrors found:\n" : "<div class='error'><strong>Errors found:</strong><ul>";
        foreach ($errors as $error) {
            echo $isCLI ? "  ✗ $error\n" : "<li>$error</li>";
        }
        echo $isCLI ? "" : "</ul></div>";
    }
    
    if (count($warnings) > 0) {
        echo $isCLI ? "\nWarnings:\n" : "<div class='warning'><strong>Warnings:</strong><ul>";
        foreach ($warnings as $warning) {
            echo $isCLI ? "  ⚠ $warning\n" : "<li>$warning</li>";
        }
        echo $isCLI ? "" : "</ul></div>";
    }
}

// Next steps
echo $isCLI ? "\n=== Next Steps ===\n" : "<h2>Next Steps</h2>";
echo $isCLI ? "1. Access your application at: http://localhost/demo-pg-01-main/\n" : "<div class='info'>";
echo $isCLI ? "2. Configure database settings in config/database.php\n" : "<ol>";
echo $isCLI ? "3. Import database schema from database/ folder\n" : "<li>Access your application at: <a href='http://localhost/demo-pg-01-main/'>http://localhost/demo-pg-01-main/</a></li>";
echo $isCLI ? "4. Test all routes by clicking the links above\n" : "<li>Configure database settings in <code>config/database.php</code></li>";
echo $isCLI ? "" : "<li>Import database schema from <code>database/</code> folder</li>";
echo $isCLI ? "" : "<li>Test all routes by clicking the links in the Available Routes table</li>";
echo $isCLI ? "" : "</ol></div>";

if (!$isCLI) {
    echo '</body></html>';
}
