<?php
session_start();
require_once 'includes/auth.php';
require_once 'config/database.php';

// Debug function
function debugLog($message) {
    error_log("DEBUG: " . $message);
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

if (!isLoggedIn()) {
    debugLog("User not logged in");
    $_SESSION['error'] = "Please login to place an order";
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debugLog("Processing order request");
    
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $payment_link = $_POST['payment_link'] ?? '';
    
    // Get cart data
    $cart_data = json_decode($_POST['cart_data'] ?? '[]', true);
    debugLog("Cart data received: " . count($cart_data) . " items");
    
    if (empty($cart_data)) {
        debugLog("Empty cart data");
        $_SESSION['error'] = "Your cart is empty!";
        header('Location: cart.php');
        exit();
    }

    // Handle file upload
    $payment_proof = '';
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        debugLog("Processing file upload");
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $filename = 'payment_' . $user_id . '_' . time() . '.' . $file_extension;
        $payment_proof = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $payment_proof)) {
            debugLog("File uploaded successfully: " . $payment_proof);
        } else {
            debugLog("File upload failed");
        }
    }

    // Calculate total from cart
    $total_amount = 0;
    foreach ($cart_data as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    debugLog("Total amount calculated: " . $total_amount);

    try {
        // Start transaction
        $pdo->beginTransaction();
        debugLog("Database transaction started");

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, payment_proof, payment_link, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $total_amount, $payment_method, $payment_proof, $payment_link]);
        $order_id = $pdo->lastInsertId();
        debugLog("Order inserted with ID: " . $order_id);

        // Insert order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_data as $item) {
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            debugLog("Order item inserted: Product " . $item['id'] . ", Qty: " . $item['quantity']);
        }

        $pdo->commit();
        debugLog("Transaction committed successfully");

        // Clear cart from localStorage using JavaScript
        echo "<script>localStorage.removeItem('cart');</script>";
        
        $_SESSION['order_success'] = "Order placed successfully! Order ID: #" . $order_id;
        debugLog("Order completed successfully, redirecting to success page");
        
        header('Location: order-success.php?id=' . $order_id);
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_msg = "Error placing order: " . $e->getMessage();
        debugLog($error_msg);
        $_SESSION['error'] = $error_msg;
        header('Location: checkout.php');
        exit();
    }
} else {
    debugLog("Invalid request method");
    header('Location: checkout.php');
    exit();
}
?>