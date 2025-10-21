<?php
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Please login to add favorites']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = $input['product_id'] ?? 0;
    $user_id = $_SESSION['user_id'];
    
    // Check if already in favorites
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already in favorites']);
        exit();
    }
    
    // Add to favorites
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);
    
    echo json_encode(['success' => true]);
    exit();
}

header('HTTP/1.1 400 Bad Request');
echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>