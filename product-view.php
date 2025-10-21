<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

$product_id = $_GET['id'] ?? 0;
$product = getProductById($product_id);

if (!$product) {
    header('Location: products.php');
    exit();
}

// Get product images (assuming images are stored as comma-separated URLs)
$images = explode(',', $product['images']);
$main_image = $images[0];
?>

<div class="product-view-container">
    <div class="product-gallery">
        <div class="main-image">
            <img src="<?php echo $main_image; ?>" alt="<?php echo $product['name']; ?>" id="mainProductImage" class="main-product-image">
        </div>
        
        <?php if (count($images) > 1): ?>
        <div class="image-thumbnails">
            <?php foreach ($images as $index => $image): ?>
            <img src="<?php echo trim($image); ?>" 
                 alt="Thumbnail <?php echo $index + 1; ?>" 
                 class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                 onclick="changeMainImage('<?php echo trim($image); ?>', this)">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="product-details">
        <h1><?php echo $product['name']; ?></h1>
        <p class="product-price"><?php echo $product['price']; ?> Birr</p>
        
        <div class="product-info">
            <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
            <p><strong>Size:</strong> <?php echo $product['size']; ?></p>
            <p><strong>Age Range:</strong> <?php echo $product['age_range']; ?></p>
            <p><strong>Gender:</strong> <?php echo ucfirst($product['gender']); ?></p>
        </div>
        
        <div class="product-actions">
            <div class="quantity-selector">
                <label>Quantity:</label>
                <input type="number" id="quantity" value="1" min="1" max="10">
            </div>
            
            <button class="btn btn-primary" onclick="addToCartFromView()">
                Add to Cart
            </button>
            
            <?php if (isLoggedIn()): ?>
            <button class="btn btn-secondary" onclick="addToFavorites(<?php echo $product['id']; ?>)">
                <i class="far fa-heart"></i> Add to Favorites
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.product-view-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.product-gallery {
    position: sticky;
    top: 2rem;
}

.main-product-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 10px;
}

.image-thumbnails {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
    border: 2px solid transparent;
}

.thumbnail.active, .thumbnail:hover {
    border-color: var(--primary-color);
}

.product-details h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.product-price {
    font-size: 2rem;
    color: var(--primary-color);
    font-weight: bold;
    margin-bottom: 2rem;
}

.product-info {
    margin-bottom: 2rem;
}

.product-info p {
    margin-bottom: 0.5rem;
}

.quantity-selector {
    margin-bottom: 1rem;
}

.quantity-selector input {
    width: 80px;
    padding: 0.5rem;
}

.product-actions .btn {
    margin-right: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .product-view-container {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function changeMainImage(imageSrc, element) {
    document.getElementById('mainProductImage').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function addToCartFromView() {
    const quantity = document.getElementById('quantity').value;
    const productId = <?php echo $product['id']; ?>;
    const productName = '<?php echo $product['name']; ?>';
    const price = <?php echo $product['price']; ?>;
    const image = '<?php echo $main_image; ?>';
    
    // Add to cart with quantity
    for (let i = 0; i < quantity; i++) {
        addToCart(productId, productName, price, image);
    }
    
    showNotification('Product added to cart!');
}
</script>

<?php require_once 'includes/footer.php'; ?>