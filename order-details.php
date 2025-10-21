<?php
require_once 'includes/auth.php';
redirectIfNotAdmin();

$order_id = $_GET['id'] ?? 0;

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email, u.phone 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: manage-orders.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.images 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="dashboard-container">
    <h1>Order Details #<?php echo $order['id']; ?></h1>
    
    <div class="admin-content">
        <div class="order-details-grid">
            <!-- Order Summary -->
            <div class="admin-card">
                <h3>Order Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Order ID:</strong> #<?php echo $order['id']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?>
                    </div>
                    <div class="info-item">
                        <strong>Status:</strong> 
                        <span class="status-badge status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Total Amount:</strong> <?php echo $order['total_amount']; ?> Birr
                    </div>
                    <div class="info-item">
                        <strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="admin-card">
                <h3>Customer Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Username:</strong> <?php echo $order['username']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Email:</strong> <?php echo $order['email']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Phone:</strong> <?php echo $order['phone'] ?: 'Not provided'; ?>
                    </div>
                </div>
            </div>
            
            <!-- Payment Proof -->
            <?php if ($order['payment_proof'] || $order['payment_link']): ?>
            <div class="admin-card">
                <h3>Payment Information</h3>
                <?php if ($order['payment_proof']): ?>
                <div class="info-item">
                    <strong>Payment Proof:</strong><br>
                    <a href="<?php echo $order['payment_proof']; ?>" target="_blank" class="btn btn-secondary">
                        View Proof
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if ($order['payment_link']): ?>
                <div class="info-item">
                    <strong>Payment Link:</strong><br>
                    <a href="<?php echo $order['payment_link']; ?>" target="_blank" class="btn btn-secondary">
                        Visit Link
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Order Items -->
        <div class="admin-card">
            <h3>Order Items</h3>
            <div class="order-items">
                <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <img src="<?php echo explode(',', $item['images'])[0] ?? 'images/placeholder.jpg'; ?>" 
                         alt="<?php echo $item['name']; ?>" 
                         class="item-image">
                    <div class="item-details">
                        <h4><?php echo $item['name']; ?></h4>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Price: <?php echo $item['price']; ?> Birr</p>
                    </div>
                    <div class="item-total">
                        <?php echo $item['price'] * $item['quantity']; ?> Birr
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    <strong>Total: <?php echo $order['total_amount']; ?> Birr</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #c3e6cb; color: #155724; }

.order-items {
    margin-top: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 1rem;
    gap: 1rem;
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.item-details {
    flex-grow: 1;
}

.item-details h4 {
    margin: 0 0 0.5rem 0;
}

.item-total {
    font-weight: bold;
    font-size: 1.1rem;
}

.order-total {
    text-align: right;
    padding: 1rem;
    border-top: 2px solid var(--light-orange);
    font-size: 1.2rem;
}
</style>

<?php require_once 'includes/footer.php'; ?>