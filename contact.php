<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);
    
    $success = "Thank you for your message! We'll get back to you soon.";
}
?>

<div class="page-container">
    <h1>Contact Us</h1>
    
    <div class="contact-content">
        <div class="contact-form-container">
            <div class="form-container">
                <h2>Send us a Message</h2>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
        
        <div class="contact-info">
            <h3>Get in Touch</h3>
            
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Address</strong>
                    <p>Addis Ababa, Ethiopia</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <strong>Phone</strong>
                    <p>+251 123 456 789</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <strong>Email</strong>
                    <p>info@michugebeya.com</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Business Hours</strong>
                    <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
                    <p>Saturday: 9:00 AM - 4:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.contact-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.contact-form-container {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.contact-info {
    background: linear-gradient(135deg, var(--light-orange), var(--light-blue));
    padding: 2rem;
    border-radius: 10px;
    height: fit-content;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.contact-item i {
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-top: 0.25rem;
}

.contact-item strong {
    display: block;
    margin-bottom: 0.25rem;
}

@media (max-width: 768px) {
    .contact-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>