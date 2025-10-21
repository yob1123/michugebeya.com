<?php
require_once 'includes/auth.php';
redirectIfNotAdmin();

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Add new product
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $age_range = $_POST['age_range'];
        $gender = $_POST['gender'];
        
        // Handle image upload (validated)
        $image_paths = [];
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = 'uploads/products/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $allowed_mime = ['image/jpeg','image/png','image/gif','image/webp'];
            $allowed_ext  = ['jpg','jpeg','png','gif','webp'];
            $max_size     = 5 * 1024 * 1024; // 5MB
            $max_files    = 6; // limit uploads per product

            $count = 0;
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($count >= $max_files) { break; }
                if (!isset($_FILES['images']['error'][$key]) || $_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) { continue; }
                if (!is_uploaded_file($tmp_name)) { continue; }
                if (!isset($_FILES['images']['size'][$key]) || $_FILES['images']['size'][$key] > $max_size) { continue; }

                // Validate it's a real image and get mime
                $info = @getimagesize($tmp_name);
                if ($info === false) { continue; }
                $mime = $info['mime'] ?? '';
                if (!in_array($mime, $allowed_mime, true)) { continue; }

                // Determine extension based on mime (fallback to original ext)
                switch ($mime) {
                    case 'image/jpeg': $ext = 'jpg'; break;
                    case 'image/png':  $ext = 'png'; break;
                    case 'image/gif':  $ext = 'gif'; break;
                    case 'image/webp': $ext = 'webp'; break;
                    default:
                        $ext = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
                }
                if (!in_array($ext, $allowed_ext, true)) { continue; }

                // Generate unique, safe filename
                $filename = 'product_' . time() . '_' . $key . '_' . uniqid() . '.' . $ext;
                $filepath = $upload_dir . $filename;

                if (move_uploaded_file($tmp_name, $filepath)) {
                    $image_paths[] = $filepath;
                    $count++;
                }
            }
        }
        
        $images_string = implode(',', $image_paths);
        
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, size, age_range, gender, images) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $size, $age_range, $gender, $images_string]);
        
        $_SESSION['success'] = "Product added successfully!";
    }
    
    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $_SESSION['success'] = "Product deleted successfully!";
    }
}

// Get all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="dashboard-container">
    <h1>Manage Products</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="admin-content">
        <!-- Add Product Form -->
        <div class="admin-card">
            <h3>Add New Product</h3>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Price (Birr) *</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Size</label>
                        <select name="size">
                            <option value="0-3m">0-3 Months</option>
                            <option value="3-6m">3-6 Months</option>
                            <option value="6-12m">6-12 Months</option>
                            <option value="1-2y">1-2 Years</option>
                            <option value="2-4y">2-4 Years</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Age Range</label>
                        <input type="text" name="age_range" placeholder="e.g., 0-3 months">
                    </div>
                    
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="boy">Boy</option>
                            <option value="girl">Girl</option>
                            <option value="unisex">Unisex</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Product Images (Multiple)</label>
                    <input type="file" name="images[]" multiple accept="image/*">
                    <small>You can select multiple images</small>
                </div>
                
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
            </form>
        </div>
        
        <!-- Products List -->
        <div class="admin-card">
            <h3>All Products (<?php echo count($products); ?>)</h3>
            
            <div class="products-list">
                <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <div class="product-info">
                        <img src="<?php echo explode(',', $product['images'])[0] ?? 'images/placeholder.jpg'; ?>" 
                             alt="<?php echo $product['name']; ?>" 
                             class="product-thumb">
                        <div class="product-details">
                            <h4><?php echo $product['name']; ?></h4>
                            <p class="product-price"><?php echo $product['price']; ?> Birr</p>
                            <p class="product-meta">
                                Size: <?php echo $product['size']; ?> | 
                                Age: <?php echo $product['age_range']; ?> | 
                                Gender: <?php echo ucfirst($product['gender']); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">Edit</a>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.admin-content {
    display: grid;
    gap: 2rem;
}

.admin-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.product-form .form-group {
    margin-bottom: 1rem;
}

.products-list {
    margin-top: 1rem;
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
}

.product-details h4 {
    margin: 0 0 0.5rem 0;
}

.product-price {
    color: var(--primary-color);
    font-weight: bold;
    margin: 0;
}

.product-meta {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #c82333;
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
    .product-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .product-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>