<?php
// Create upload directories if they don't exist
$directories = [
    'uploads',
    'uploads/products',
    'uploads/payments',
    'images',
    'images/products'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "Created directory: $dir<br>";
        } else {
            echo "Failed to create directory: $dir<br>";
        }
    } else {
        echo "Directory already exists: $dir<br>";
    }
}

echo "Upload directories setup complete!";
?>