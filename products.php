<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get filter parameters
$size = $_GET['size'] ?? '';
$gender = $_GET['gender'] ?? '';
$price_range = $_GET['price_range'] ?? '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($size)) {
    $sql .= " AND size = ?";
    $params[] = $size;
}

if (!empty($gender)) {
    $sql .= " AND gender = ?";
    $params[] = $gender;
}

if (!empty($price_range)) {
    $ranges = explode('-', $price_range);
    $sql .= " AND price BETWEEN ? AND ?";
    $params[] = $ranges[0];
    $params[] = $ranges[1];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="products-header">
    <h1>Our Products</h1>
    
    <form method="GET" class="filter-form" id="filterForm">
        <div class="filter-group">
            <!-- Lock/Unlock filter controls -->
            <button type="button" id="filterLockToggle" title="Lock filters" class="btn btn-lock">
                <span id="filterLockIcon">🔓</span>
            </button>
            <select name="size">
                <option value="">All Sizes</option>
                <option value="0-3m" <?php echo $size=='0-3m'?'selected':''; ?>>0-3 Months</option>
                <option value="3-6m" <?php echo $size=='3-6m'?'selected':''; ?>>3-6 Months</option>
                <option value="6-12m" <?php echo $size=='6-12m'?'selected':''; ?>>6-12 Months</option>
                <option value="1-2y" <?php echo $size=='1-2y'?'selected':''; ?>>1-2 Years</option>
                <option value="2-4y" <?php echo $size=='2-4y'?'selected':''; ?>>2-4 Years</option>
            </select>
            
            <select name="gender">
                <option value="">All Gender</option>
                <option value="boy" <?php echo $gender=='boy'?'selected':''; ?>>Boy</option>
                <option value="girl" <?php echo $gender=='girl'?'selected':''; ?>>Girl</option>
                <option value="unisex" <?php echo $gender=='unisex'?'selected':''; ?>>Unisex</option>
            </select>
            
            <select name="price_range">
                <option value="">All Prices</option>
                <option value="0-500" <?php echo $price_range=='0-500'?'selected':''; ?>>0 - 500 Birr</option>
                <option value="500-1000" <?php echo $price_range=='500-1000'?'selected':''; ?>>500 - 1000 Birr</option>
                <option value="1000-2000" <?php echo $price_range=='1000-2000'?'selected':''; ?>>1000 - 2000 Birr</option>
                <option value="2000-5000" <?php echo $price_range=='2000-5000'?'selected':''; ?>>2000 - 5000 Birr</option>
            </select>
            
            <button type="submit" class="btn" id="applyFilters">Apply Filters</button>
            <a href="products.php" class="btn btn-secondary" id="clearFilters">Clear</a>
        </div>
    </form>
</div>

<div class="products-grid">
    <?php if (empty($products)): ?>
        <p>No products found matching your criteria.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo $product['images'] ?: 'images/placeholder.jpg'; ?>" 
                 alt="<?php echo $product['name']; ?>" 
                 class="product-image"
                 onclick="viewProduct(<?php echo $product['id']; ?>)">
            <h3><?php echo $product['name']; ?></h3>
            <p class="product-description"><?php echo substr($product['description'], 0, 100); ?>...</p>
            <p class="product-size">Size: <?php echo $product['size']; ?></p>
            <p class="product-age">Age: <?php echo $product['age_range']; ?></p>
            <p class="product-price"><?php echo $product['price']; ?> Birr</p>
            
            <div class="product-actions">
                <button class="btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['images']; ?>')">
                    Add to Cart
                </button>
                <?php if (isLoggedIn()): ?>
                <button class="btn btn-secondary" onclick="addToFavorites(<?php echo $product['id']; ?>)">
                    <i class="far fa-heart"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function viewProduct(productId) {
    window.location.href = 'product-view.php?id=' + productId;
}

function addToCart(productId, productName, price, image) {
    // Cart functionality from script.js
    addToCart(productId, productName, price, image);
}

function addToFavorites(productId) {
    // AJAX call to add to favorites
    fetch('add-favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({product_id: productId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to favorites!');
        } else {
            showNotification('Error adding to favorites');
        }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>