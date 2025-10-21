<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$order_id = $_GET['id'] ?? 0;

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: profile.php');
    exit();
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-container">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Order Placed Successfully!</h1>
        <p class="success-message">Thank you for your order. Your order has been received and is being processed.</p>
        
        <div class="order-summary">
            <div class="summary-item">
                <strong>Order Number:</strong>
                <span>#<?php echo $order['id']; ?></span>
            </div>
            <div class="summary-item">
                <strong>Total Amount:</strong>
                <span><?php echo $order['total_amount']; ?> Birr</span>
            </div>
            <div class="summary-item">
                <strong>Payment Method:</strong>
                <span><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
            </div>
            <div class="summary-item">
                <strong>Order Status:</strong>
                <span class="status-badge status-<?php echo $order['status']; ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
        </div>
        
        <div class="success-actions">
            <a href="profile.php" class="btn btn-primary">View My Orders</a>
            <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
        
        <div class="next-steps">
            <h3>What's Next?</h3>
            <ul>
                <li>You will receive an order confirmation email shortly</li>
                <li>We will process your order within 24 hours</li>
                <li>You can track your order status in your account</li>
                <li>For any questions, contact our support team</li>
            </ul>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 600px;
    margin: 2rem auto;
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.success-message {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    color: #666;
}

.order-summary {
    background: var(--light-blue);
    padding: 1.5rem;
    border-radius: 8px;
    margin: 2rem 0;
    text-align: left;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.3);
}

.summary-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
}

.next-steps {
    text-align: left;
    background: var(--light-orange);
    padding: 1.5rem;
    border-radius: 8px;
}

.next-steps h3 {
    margin-bottom: 1rem;
    color: var(--text-color);
}

.next-steps ul {
    list-style-type: none;
    padding: 0;
}

.next-steps li {
    padding: 0.5rem 0;
    padding-left: 1.5rem;
    position: relative;
}

.next-steps li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--primary-color);
    font-weight: bold;
}

@media (max-width: 768px) {
    .success-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>