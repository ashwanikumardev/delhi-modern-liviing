<?php
/**
 * Database Setup Script
 * This script will create the database and import the schema
 */

// Configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'delhi_modern_living';
$schemaFile = __DIR__ . '/database/schema.sql';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Setup - Delhi Modern Living</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #2563eb; }
        .success { color: #059669; background: #d1fae5; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc2626; background: #fee2e2; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #2563eb; background: #dbeafe; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .step { background: #f3f4f6; padding: 10px; margin: 10px 0; border-left: 4px solid #2563eb; }
        pre { background: #1f2937; color: #f3f4f6; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 10px 5px 10px 0; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <h1>🗄️ Database Setup</h1>";

// Check if schema file exists
if (!file_exists($schemaFile)) {
    echo "<div class='error'>
        <strong>Error:</strong> Schema file not found at: <code>$schemaFile</code>
    </div>
    </body></html>";
    exit;
}

try {
    // Step 1: Connect to MySQL (without database)
    echo "<div class='step'><strong>Step 1:</strong> Connecting to MySQL...</div>";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='success'>✓ Connected to MySQL successfully</div>";

    // Step 2: Create database if it doesn't exist
    echo "<div class='step'><strong>Step 2:</strong> Creating database '$database'...</div>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<div class='success'>✓ Database '$database' created/verified</div>";

    // Step 3: Select database
    echo "<div class='step'><strong>Step 3:</strong> Selecting database...</div>";
    $pdo->exec("USE `$database`");
    echo "<div class='success'>✓ Database selected</div>";

    // Step 4: Read and execute schema
    echo "<div class='step'><strong>Step 4:</strong> Importing schema...</div>";
    $sql = file_get_contents($schemaFile);
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   !preg_match('/^--/', $stmt) && 
                   !preg_match('/^CREATE DATABASE/', $stmt) &&
                   !preg_match('/^USE /', $stmt);
        }
    );

    $successCount = 0;
    $errorCount = 0;
    $errors = [];

    foreach ($statements as $statement) {
        try {
            $pdo->exec($statement);
            $successCount++;
        } catch (PDOException $e) {
            // Ignore "table already exists" errors
            if (strpos($e->getMessage(), 'already exists') === false) {
                $errorCount++;
                $errors[] = $e->getMessage();
            } else {
                $successCount++;
            }
        }
    }

    echo "<div class='success'>
        ✓ Schema imported successfully<br>
        <small>Executed: $successCount statements</small>
    </div>";

    if ($errorCount > 0) {
        echo "<div class='error'>
            <strong>Warnings:</strong> $errorCount statements had issues<br>
            <details>
                <summary>Show details</summary>
                <pre>" . implode("\n\n", $errors) . "</pre>
            </details>
        </div>";
    }

    // Step 5: Verify tables
    echo "<div class='step'><strong>Step 5:</strong> Verifying tables...</div>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='success'>
        ✓ Found " . count($tables) . " tables:<br>
        <small>" . implode(', ', $tables) . "</small>
    </div>";

    // Step 6: Check for admin user
    echo "<div class='step'><strong>Step 6:</strong> Checking for admin user...</div>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin' OR role = 'super_admin'");
    $adminCount = $stmt->fetchColumn();

    if ($adminCount == 0) {
        echo "<div class='info'>
            <strong>ℹ️ No admin user found.</strong><br>
            You should create an admin account. Here's a sample SQL to create one:
            <pre>INSERT INTO users (name, email, phone, password_hash, role, status, email_verified)
VALUES (
    'Admin User',
    'admin@example.com',
    '9876543210',
    '" . password_hash('admin123', PASSWORD_DEFAULT) . "',
    'super_admin',
    'active',
    TRUE
);</pre>
            <small>Default credentials: admin@example.com / admin123</small>
        </div>";
    } else {
        echo "<div class='success'>✓ Found $adminCount admin user(s)</div>";
    }

    // Success message
    echo "<div class='success' style='margin-top: 30px; font-size: 18px;'>
        <strong>🎉 Database Setup Complete!</strong><br><br>
        Your database is ready to use.
    </div>";

    echo "<div class='info'>
        <strong>📋 Database Configuration:</strong><br>
        Host: <code>$host</code><br>
        Database: <code>$database</code><br>
        Username: <code>$username</code><br>
        Password: <code>" . (empty($password) ? '(empty)' : '***') . "</code>
    </div>";

    echo "<div style='margin-top: 30px;'>
        <a href='index.php' class='btn'>🏠 Go to Home Page</a>
        <a href='verify-setup.php' class='btn'>✓ Verify Setup</a>
        <a href='http://localhost/phpmyadmin' class='btn' target='_blank'>🗄️ Open phpMyAdmin</a>
    </div>";

} catch (PDOException $e) {
    echo "<div class='error'>
        <strong>❌ Database Error:</strong><br>
        " . htmlspecialchars($e->getMessage()) . "
    </div>";

    echo "<div class='info'>
        <strong>💡 Troubleshooting:</strong>
        <ol>
            <li>Make sure MySQL is running in XAMPP Control Panel</li>
            <li>Verify database credentials in <code>config/database.php</code></li>
            <li>Check if port 3306 is not blocked</li>
            <li>Try accessing phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>
        </ol>
    </div>";
}

echo "</body></html>";
