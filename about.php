<?php require_once 'includes/header.php'; ?>

<div class="page-container">
    <h1>About Michu Gebeya</h1>
    
    <div class="about-content">
        <div class="about-hero">
            <div class="about-text">
                <h2>Your Trusted Partner for Children & Infant Clothing</h2>
                <p>Michu Gebeya is Ethiopia's premier online destination for quality children and infant clothing. We understand that your little ones deserve the best, which is why we carefully select each item in our collection for comfort, quality, and style.</p>
                
                <p>Founded in 2024, our mission is to provide Ethiopian parents with access to affordable, high-quality clothing that meets international standards while supporting local communities.</p>
            </div>
            <div class="about-image">
                <img src="images/about-hero.jpg" alt="About Michu Gebeya" style="width: 100%; border-radius: 10px;">
            </div>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-tshirt"></i>
                <h3>Quality Materials</h3>
                <p>All our clothes are made from soft, breathable fabrics that are gentle on your child's skin.</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Ethically Sourced</h3>
                <p>We work with suppliers who maintain high ethical standards in their manufacturing processes.</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-shipping-fast"></i>
                <h3>Fast Delivery</h3>
                <p>Quick and reliable delivery across Ethiopia with multiple payment options.</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-headset"></i>
                <h3>Customer Support</h3>
                <p>Our dedicated support team is here to help you with any questions or concerns.</p>
            </div>
        </div>
        
        <div class="mission-section">
            <h2>Our Mission & Vision</h2>
            <div class="mission-content">
                <div class="mission-item">
                    <h3>Mission</h3>
                    <p>To provide Ethiopian families with access to high-quality, affordable children's clothing that combines comfort, style, and durability while promoting local economic growth.</p>
                </div>
                <div class="mission-item">
                    <h3>Vision</h3>
                    <p>To become Ethiopia's most trusted children's clothing brand, known for quality, customer service, and community impact.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.about-content {
    margin-top: 2rem;
}

.about-hero {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    align-items: center;
    margin-bottom: 3rem;
}

.about-text h2 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.about-text p {
    margin-bottom: 1rem;
    line-height: 1.8;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 1rem;
    color: var(--text-color);
}

.mission-section {
    margin: 3rem 0;
    padding: 2rem;
    background: linear-gradient(135deg, var(--light-orange), var(--light-blue));
    border-radius: 10px;
}

.mission-section h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--text-color);
}

.mission-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.mission-item {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.mission-item h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .about-hero {
        grid-template-columns: 1fr;
    }
    
    .mission-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>