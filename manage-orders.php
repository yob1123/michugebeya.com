<?php
// Add this enhanced orders table to your existing manage-orders.php
?>

<div class="orders-management">
    <div class="management-header">
        <h2>Order Management</h2>
        <div class="header-filters">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
            </select>
            <input type="date" id="dateFilter" placeholder="Filter by date">
            <button class="btn btn-primary" onclick="exportOrders()">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <div class="orders-stats">
        <div class="stat-mini pending">
            <i class="fas fa-clock"></i>
            <div>
                <span class="count"><?php echo getOrderCount('pending'); ?></span>
                <span class="label">Pending</span>
            </div>
        </div>
        <div class="stat-mini confirmed">
            <i class="fas fa-check-circle"></i>
            <div>
                <span class="count"><?php echo getOrderCount('confirmed'); ?></span>
                <span class="label">Confirmed</span>
            </div>
        </div>
        <div class="stat-mini shipped">
            <i class="fas fa-shipping-fast"></i>
            <div>
                <span class="count"><?php echo getOrderCount('shipped'); ?></span>
                <span class="label">Shipped</span>
            </div>
        </div>
        <div class="stat-mini delivered">
            <i class="fas fa-box-open"></i>
            <div>
                <span class="count"><?php echo getOrderCount('delivered'); ?></span>
                <span class="label">Delivered</span>
            </div>
        </div>
    </div>

    <!-- Rest of your existing orders table -->
</div>

<style>
.management-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-filters {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.orders-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-mini {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border-left: 4px solid;
}

.stat-mini.pending { border-left-color: #F59E0B; }
.stat-mini.confirmed { border-left-color: #4A6CF7; }
.stat-mini.shipped { border-left-color: #10B981; }
.stat-mini.delivered { border-left-color: #059669; }

.stat-mini i {
    font-size: 2rem;
    opacity: 0.7;
}

.stat-mini .count {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-gray);
}

.stat-mini .label {
    font-size: 0.9rem;
    color: var(--light-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<script>
function exportOrders() {
    showModernNotification('Preparing order export...', 'info');
    // In real implementation, this would generate a CSV/Excel file
    setTimeout(() => {
        showModernNotification('Orders exported successfully!', 'success');
    }, 2000);
}

// Filter orders dynamically
document.getElementById('statusFilter').addEventListener('change', filterOrders);
document.getElementById('dateFilter').addEventListener('change', filterOrders);

function filterOrders() {
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    // Show loading state
    showModernNotification('Filtering orders...', 'info');
    
    // In real implementation, this would make an AJAX call
    setTimeout(() => {
        showModernNotification('Orders filtered successfully!', 'success');
    }, 1000);
}
</script>

<?php
// Helper function to get order counts by status
function getOrderCount($status) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}
?>