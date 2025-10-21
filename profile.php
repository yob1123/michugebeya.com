<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

// Get user orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user favorites
$stmt = $pdo->prepare("
    SELECT f.*, p.name, p.price, p.images 
    FROM favorites f 
    LEFT JOIN products p ON f.product_id = p.id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC
");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-container">
    <h1>My Account</h1>
    
    <div class="profile-content">
        <div class="profile-sidebar">
            <div class="user-info">
                <h3>Welcome, <?php echo $_SESSION['username']; ?>!</h3>
                <p>Member since <?php echo date('F Y', strtotime($_SESSION['created_at'] ?? 'now')); ?></p>
            </div>
            
            <nav class="profile-nav">
                <a href="#orders" class="nav-link active">My Orders</a>
                <a href="#favorites" class="nav-link">My Favorites</a>
                <a href="#settings" class="nav-link">Account Settings</a>
            </nav>
        </div>
        
        <div class="profile-main">
            <!-- Orders Section -->
            <section id="orders" class="profile-section active">
                <h2>My Orders</h2>
                
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <h3>No orders yet</h3>
                        <p>Start shopping to see your orders here!</p>
                        <a href="products.php" class="btn">Start Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <strong>Order #<?php echo $order['id']; ?></strong>
                                    <p>Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                    <p class="order-total"><?php echo $order['total_amount']; ?> Birr</p>
                                </div>
                            </div>
                            
                            <div class="order-actions">
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary">View Details</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            
            <!-- Favorites Section -->
            <section id="favorites" class="profile-section">
                <h2>My Favorites</h2>
                
                <?php if (empty($favorites)): ?>
                    <div class="empty-state">
                        <h3>No favorites yet</h3>
                        <p>Add some products to your favorites!</p>
                        <a href="products.php" class="btn">Browse Products</a>
                    </div>
                <?php else: ?>
                    <div class="favorites-grid">
                        <?php foreach ($favorites as $favorite): ?>
                        <div class="favorite-item">
                            <img src="<?php echo explode(',', $favorite['images'])[0] ?? 'images/placeholder.jpg'; ?>" 
                                 alt="<?php echo $favorite['name']; ?>" 
                                 class="favorite-image">
                            <div class="favorite-details">
                                <h4><?php echo $favorite['name']; ?></h4>
                                <p class="favorite-price"><?php echo $favorite['price']; ?> Birr</p>
                            </div>
                            <div class="favorite-actions">
                                <a href="product-view.php?id=<?php echo $favorite['product_id']; ?>" class="btn btn-secondary">View</a>
                                <button class="btn btn-danger" onclick="removeFavorite(<?php echo $favorite['id']; ?>)">Remove</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            
            <!-- Settings Section -->
            <section id="settings" class="profile-section">
                <h2>Account Settings</h2>
                <div class="settings-form">
                    <form>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="<?php echo $_SESSION['username']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Change Password</label>
                            <input type="password" placeholder="New password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn">Update Settings</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
.profile-content {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.profile-sidebar {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.user-info {
    text-align: center;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
    margin-bottom: 1rem;
}

.profile-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-nav .nav-link {
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: var(--text-color);
    border-radius: 5px;
    transition: background 0.3s ease;
}

.profile-nav .nav-link:hover,
.profile-nav .nav-link.active {
    background: var(--light-blue);
    color: var(--primary-color);
}

.profile-main {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.profile-section {
    display: none;
}

.profile-section.active {
    display: block;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #666;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-card {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 1.5rem;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-status {
    text-align: right;
}

.order-total {
    font-weight: bold;
    color: var(--primary-color);
    margin-top: 0.5rem;
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.favorite-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
}

.favorite-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.favorite-details {
    flex-grow: 1;
}

.favorite-price {
    color: var(--primary-color);
    font-weight: bold;
}

.favorite-actions {
    display: flex;
    gap: 0.5rem;
}

.settings-form {
    max-width: 500px;
}

@media (max-width: 768px) {
    .profile-content {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        position: static;
    }
    
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-status {
        text-align: left;
    }
    
    .favorite-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
// Tab navigation
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.profile-nav .nav-link');
    const sections = document.querySelectorAll('.profile-section');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and sections
            navLinks.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Show corresponding section
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).classList.add('active');
        });
    });
});

function removeFavorite(favoriteId) {
    if (confirm('Remove from favorites?')) {
        fetch('remove-favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({favorite_id: favoriteId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Removed from favorites');
                location.reload();
            }
        });
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>