<?php
function getFeaturedProducts() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting featured products: " . $e->getMessage());
        return [];
    }
}

function getProductById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting product by ID: " . $e->getMessage());
        return false;
    }
}

function getOrderCount($status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting order count: " . $e->getMessage());
        return 0;
    }
}

function getRecentOrders($limit = 5) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT o.*, u.username 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting recent orders: " . $e->getMessage());
        return [];
    }
}

function getMonthlyRevenue() {
    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_amount) as revenue,
                COUNT(*) as orders
            FROM orders 
            WHERE status = 'delivered'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
            LIMIT 6
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting monthly revenue: " . $e->getMessage());
        return [];
    }
}

function getOrderStatusDistribution() {
    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT status, COUNT(*) as count 
            FROM orders 
            GROUP BY status
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting order status distribution: " . $e->getMessage());
        return [];
    }
}

function getPopularProducts() {
    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT p.name, COUNT(oi.product_id) as sales
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            GROUP BY oi.product_id
            ORDER BY sales DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting popular products: " . $e->getMessage());
        return [];
    }
}

function getAllProducts($filters = []) {
    global $pdo;
    
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    
    if (!empty($filters['size'])) {
        $sql .= " AND size = ?";
        $params[] = $filters['size'];
    }
    
    if (!empty($filters['gender'])) {
        $sql .= " AND gender = ?";
        $params[] = $filters['gender'];
    }
    
    if (!empty($filters['price_range'])) {
        $priceRange = explode('-', $filters['price_range']);
        if (count($priceRange) === 2) {
            $sql .= " AND price BETWEEN ? AND ?";
            $params[] = $priceRange[0];
            $params[] = $priceRange[1];
        }
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting filtered products: " . $e->getMessage());
        return [];
    }
}

// Debug function to check database connection
function checkDatabaseConnection() {
    global $pdo;
    try {
        $pdo->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return false;
    }
}
?>