<?php
// This file handles AJAX cart updates
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $product_id = $_POST['product_id'] ?? 0;
            $product_name = $_POST['product_name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? '';
            
            // Add to cart logic (using session or database)
            echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            break;
            
        case 'remove':
            $product_id = $_POST['product_id'] ?? 0;
            // Remove from cart logic
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>