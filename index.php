<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';
?>

<!-- Modern Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1 class="fade-in-up">Welcome to <span style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">ምቹ ገበያ</span></h1>
        <p class="fade-in-up">Discover the perfect blend of style and comfort for your little ones. Quality children's clothing that grows with them.</p>
        <div class="hero-actions fade-in-up">
            <a href="products.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                Shop Collection
            </a>
            <a href="#featured" class="btn btn-outline">
                <i class="fas fa-star"></i>
                Featured Products
            </a>
        </div>
    </div>
    
    <!-- Floating elements for visual interest -->
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Premium Quality</h3>
                <p>All products made with child-safe, breathable materials</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>Quick and reliable delivery across Ethiopia</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h3>Perfect Fit</h3>
                <p>Clothes designed specifically for Ethiopian children</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Dedicated customer service team always ready to help</p>
            </div>
        </div>
    </div>
</section>
<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>Stay Updated</h2>
            <p>Get the latest updates on new collections and exclusive offers</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Subscribe
                </button>
            </form>
        </div>
    </div>
</section>

<style>
/* Additional Modern Styles */
.hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(45deg, var(--primary-orange), var(--primary-blue));
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.features-section {
    padding: 4rem 2rem;
    background: var(--light-orange);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.feature-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--dark-gray);
}

.feature-card p {
    color: var(--light-gray);
    line-height: 1.6;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: var(--border-radius);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.section-cta {
    text-align: center;
    margin-top: 3rem;
}

.newsletter-section {
    background: var(--gradient-secondary);
    padding: 4rem 2rem;
    color: white;
    text-align: center;
}

.newsletter-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.newsletter-content p {
    font-size: 1.125rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.newsletter-form {
    display: flex;
    gap: 1rem;
    max-width: 500px;
    margin: 0 auto;
}

.newsletter-form input {
    flex: 1;
    padding: 1rem;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
}

.newsletter-form input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
}

@media (max-width: 768px) {
    .newsletter-form {
        flex-direction: column;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-actions .btn {
        width: 200px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>