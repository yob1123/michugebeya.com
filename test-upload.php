<?php
// Test if upload directories are working
$test_dirs = ['uploads', 'uploads/products', 'uploads/payments'];

foreach ($test_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✓ $dir exists and is writable<br>";
        } else {
            echo "✗ $dir exists but is NOT writable<br>";
        }
    } else {
        echo "✗ $dir does NOT exist<br>";
    }
}

// Test file upload
if ($_FILES['test_file'] ?? false) {
    $target_dir = "uploads/products/";
    $target_file = $target_dir . "test_" . time() . ".txt";
    
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $target_file)) {
        echo "✓ File upload test successful!<br>";
        unlink($target_file); // Clean up
    } else {
        echo "✗ File upload test failed!<br>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="test_file">
    <input type="submit" value="Test Upload">
</form>