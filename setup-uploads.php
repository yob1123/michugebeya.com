<?php
// Quick setup script for upload directories
function setupUploadDirs() {
    $dirs = [
        'uploads',
        'uploads/products',
        'uploads/payments',
        'images',
        'images/products'
    ];
    
    $results = [];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                $results[] = "✅ Created: $dir";
                
                // Add .htaccess for security
                if (strpos($dir, 'uploads') !== false) {
                    file_put_contents("$dir/.htaccess", 
                        "<FilesMatch \"\\.(php|php5|phtml)$\">\n" .
                        "    Order Allow,Deny\n" .
                        "    Deny from all\n" .
                        "</FilesMatch>");
                    $results[] = "✅ Added security: $dir/.htaccess";
                }
                
                // Add index.html to prevent directory listing
                file_put_contents("$dir/index.html", 
                    "<!DOCTYPE html>\n<html>\n<head>\n    <title>403 Forbidden</title>\n</head>\n<body>\n    <h1>Access Forbidden</h1>\n</body>\n</html>");
                $results[] = "✅ Added index: $dir/index.html";
                
            } else {
                $results[] = "❌ Failed to create: $dir";
            }
        } else {
            $results[] = "ℹ️ Already exists: $dir";
        }
    }
    
    return $results;
}

echo "<h2>Setting up upload directories...</h2>";
$results = setupUploadDirs();
echo "<pre>" . implode("\n", $results) . "</pre>";
echo "<h3>Setup complete!</h3>";
?>