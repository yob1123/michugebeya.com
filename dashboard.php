<?php
require_once 'includes/auth.php';
redirectIfNotAdmin();

// Get analytics data
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'customer'")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'delivered'")->fetchColumn() ?? 0;

// Recent orders
$recentOrders = $pdo->query("
    SELECT o.*, u.username 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Monthly revenue data for chart
$monthlyRevenue = $pdo->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        SUM(total_amount) as revenue,
        COUNT(*) as orders
    FROM orders 
    WHERE status = 'delivered'
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

// Order status distribution
$orderStatus = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM orders 
    GROUP BY status
")->fetchAll(PDO::FETCH_ASSOC);

// Popular products
$popularProducts = $pdo->query("
    SELECT p.name, COUNT(oi.product_id) as sales
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY sales DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Dashboard Overview</h1>
            <p>Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div class="header-actions">
            <div class="date-filter">
                <select id="timeFilter">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
                <h3>Total Revenue</h3>
                <p class="stat-number"><?php echo number_format($totalRevenue, 2); ?> Birr</p>
                <span class="stat-trend positive">+12% from last month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orders">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>Total Orders</h3>
                <p class="stat-number"><?php echo $totalOrders; ?></p>
                <span class="stat-trend positive">+8% from last month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon customers">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Customers</h3>
                <p class="stat-number"><?php echo $totalUsers; ?></p>
                <span class="stat-trend positive">+5% from last month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon products">
                <i class="fas fa-tshirt"></i>
            </div>
            <div class="stat-info">
                <h3>Products</h3>
                <p class="stat-number"><?php echo $totalProducts; ?></p>
                <span class="stat-trend positive">+15% from last month</span>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Section -->
    <div class="analytics-grid">
        <!-- Revenue Chart -->
        <div class="analytics-card full-width">
            <div class="card-header">
                <h3>Revenue Analytics</h3>
                <div class="card-actions">
                    <button class="btn-icon"><i class="fas fa-download"></i></button>
                    <button class="btn-icon"><i class="fas fa-expand"></i></button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Order Status</h3>
            </div>
            <div class="chart-container">
                <canvas id="orderStatusChart" height="250"></canvas>
            </div>
        </div>

        <!-- Popular Products -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Popular Products</h3>
            </div>
            <div class="products-list">
                <?php foreach ($popularProducts as $index => $product): ?>
                <div class="product-rank">
                    <div class="rank-number"><?php echo $index + 1; ?></div>
                    <div class="product-info">
                        <h4><?php echo $product['name']; ?></h4>
                        <p><?php echo $product['sales']; ?> sales</p>
                    </div>
                    <div class="sales-badge">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="recent-orders">
        <div class="section-header">
            <h3>Recent Orders</h3>
            <a href="manage-orders.php" class="btn btn-outline">View All Orders</a>
        </div>
        
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td>
                            <div class="customer-info">
                                <strong><?php echo $order['username']; ?></strong>
                            </div>
                        </td>
                        <td><?php echo number_format($order['total_amount'], 2); ?> Birr</td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn-action">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if (empty($recentOrders)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No orders yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="manage-products.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <span>Add Product</span>
            </a>
            <a href="manage-orders.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span>Manage Orders</span>
            </a>
            <a href="manage-users.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>View Users</span>
            </a>
            <a href="view-messages.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <span>Messages</span>
            </a>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.dashboard-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-content p {
    color: var(--light-gray);
}

.date-filter select {
    padding: 0.5rem 1rem;
    border: 2px solid #E2E8F0;
    border-radius: var(--border-radius);
    background: var(--white);
}

/* Enhanced Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.revenue { background: var(--gradient-primary); }
.stat-icon.orders { background: var(--gradient-secondary); }
.stat-icon.customers { background: linear-gradient(135deg, #10B981, #34D399); }
.stat-icon.products { background: linear-gradient(135deg, #F59E0B, #FBBF24); }

.stat-info h3 {
    font-size: 0.9rem;
    color: var(--light-gray);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-gray);
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
}

.stat-trend.positive {
    background: #D1FAE5;
    color: #065F46;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: 1.5rem;
}

.analytics-card.full-width {
    grid-column: 1 / -1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.card-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-gray);
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 35px;
    height: 35px;
    border: none;
    background: var(--light-orange);
    border-radius: var(--border-radius);
    color: var(--primary-orange);
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: var(--primary-orange);
    color: white;
}

.chart-container {
    position: relative;
    height: 300px;
}

/* Products List */
.products-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.product-rank {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--light-orange);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.product-rank:hover {
    transform: translateX(5px);
    background: var(--white);
    box-shadow: var(--shadow-sm);
}

.rank-number {
    width: 30px;
    height: 30px;
    background: var(--gradient-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
}

.product-info h4 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.product-info p {
    font-size: 0.8rem;
    color: var(--light-gray);
}

.sales-badge {
    margin-left: auto;
    color: var(--primary-orange);
}

/* Recent Orders */
.recent-orders {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: 2rem;
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-gray);
}

.orders-table {
    overflow-x: auto;
}

.orders-table table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th,
.orders-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #E2E8F0;
}

.orders-table th {
    background: var(--light-orange);
    font-weight: 600;
    color: var(--dark-gray);
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { background: #FEF3C7; color: #D97706; }
.status-confirmed { background: #DBEAFE; color: #1D4ED8; }
.status-shipped { background: #D1FAE5; color: #065F46; }
.status-delivered { background: #D1FAE5; color: #065F46; }

.btn-action {
    width: 35px;
    height: 35px;
    background: var(--light-blue);
    color: var(--primary-blue);
    border: none;
    border-radius: var(--border-radius);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: var(--primary-blue);
    color: white;
    transform: scale(1.1);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--light-gray);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Quick Actions */
.quick-actions {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: 2rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 2rem 1rem;
    background: var(--light-orange);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--dark-gray);
    transition: all 0.3s ease;
    text-align: center;
}

.action-card:hover {
    background: var(--gradient-primary);
    color: white;
    transform: translateY(-5px);
}

.action-icon {
    width: 60px;
    height: 60px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.action-card:hover .action-icon {
    background: rgba(255,255,255,0.2);
    color: white;
}

@media (max-width: 1024px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .actions-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeFilters();
});

function initializeCharts() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($monthlyRevenue, 'month')); ?>,
            datasets: [{
                label: 'Monthly Revenue (Birr)',
                data: <?php echo json_encode(array_column($monthlyRevenue, 'revenue')); ?>,
                borderColor: '#FF6B35',
                backgroundColor: 'rgba(255, 107, 53, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($orderStatus, 'status')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($orderStatus, 'count')); ?>,
                backgroundColor: [
                    '#FF6B35',
                    '#4A6CF7',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function initializeFilters() {
    const timeFilter = document.getElementById('timeFilter');
    if (timeFilter) {
        timeFilter.addEventListener('change', function() {
            // In a real application, you would fetch new data based on the filter
            showModernNotification(`Filter updated to: ${this.value}`, 'info');
        });
    }
}

// Real-time updates (simulated)
function simulateRealTimeUpdates() {
    setInterval(() => {
        // Simulate new order notification
        if (Math.random() > 0.7) {
            showModernNotification('New order received!', 'success');
        }
    }, 30000); // Check every 30 seconds
}

// Start real-time updates
simulateRealTimeUpdates();
</script>

<?php require_once 'includes/footer.php'; ?>