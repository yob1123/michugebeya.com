<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    $_SESSION['redirect_to'] = 'checkout.php';
    header('Location: login.php');
    exit();
}

// Get cart from localStorage via JavaScript
?>
<div class="page-container">
    <h1>Checkout</h1>
    
    <div class="checkout-content">
        <div class="checkout-form">
            <form id="checkoutForm" method="POST" action="process-order.php" enctype="multipart/form-data">
                <h3>Shipping Information</h3>
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Address *</label>
                    <textarea name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="city" required>
                </div>
                
                <h3>Payment Method</h3>
                <div class="form-group">
                    <select name="payment_method" id="paymentMethod" required onchange="togglePaymentProof()">
                        <option value="">Select Payment Method</option>
                        <option value="online">Online Payment</option>
                        <option value="on_arrival">Pay on Arrival</option>
                    </select>
                </div>
                
                <div id="paymentProofSection" style="display: none;">
                    <div class="form-group">
                        <label>Payment Proof (Screenshot/PDF) *</label>
                        <input type="file" name="payment_proof" id="paymentProof" accept=".jpg,.jpeg,.png,.pdf">
                        <small>Upload screenshot of payment or PDF receipt</small>
                    </div>
                    <div class="form-group">
                        <label>Or Payment Link</label>
                        <input type="url" name="payment_link" placeholder="https://...">
                    </div>
                </div>
                
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div id="checkoutSummary">
                        <!-- Order summary will be loaded via JavaScript -->
                    </div>
                </div>
                
                <input type="hidden" name="cart_data" id="cartData">
                
                <button type="submit" class="btn btn-primary" id="placeOrderBtn">
                    <i class="fas fa-shopping-bag"></i> Place Order
                </button>
            </form>
        </div>
        
        <div class="order-review">
            <h3>Your Order</h3>
            <div id="orderReview">
                <!-- Order items will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<style>
.checkout-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.checkout-form {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.order-review {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.order-total {
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid var(--light-orange);
}

.empty-cart-message {
    text-align: center;
    padding: 2rem;
    color: var(--light-gray);
}

.empty-cart-message a {
    display: inline-block;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCheckoutSummary();
    setupFormValidation();
});

function loadCheckoutSummary() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const checkoutSummary = document.getElementById('checkoutSummary');
    const orderReview = document.getElementById('orderReview');
    const cartDataInput = document.getElementById('cartData');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    
    // Store cart data in hidden input
    cartDataInput.value = JSON.stringify(cart);
    
    if (cart.length === 0) {
        checkoutSummary.innerHTML = `
            <div class="empty-cart-message">
                <h4>Your cart is empty</h4>
                <p>Add some products to your cart first!</p>
                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        `;
        orderReview.innerHTML = '<p>Your cart is empty</p>';
        placeOrderBtn.disabled = true;
        return;
    }
    
    let subtotal = 0;
    let summaryHTML = '';
    let reviewHTML = '';
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        summaryHTML += `
            <div class="order-item">
                <span>${item.name} x ${item.quantity}</span>
                <span>${itemTotal.toFixed(2)} Birr</span>
            </div>
        `;
        
        reviewHTML += `
            <div class="order-item">
                <div>
                    <strong>${item.name}</strong><br>
                    <small>Qty: ${item.quantity} × ${item.price} Birr</small>
                </div>
                <span>${itemTotal.toFixed(2)} Birr</span>
            </div>
        `;
    });
    
    summaryHTML += `
        <div class="order-total">
            <span>Total:</span>
            <span>${subtotal.toFixed(2)} Birr</span>
        </div>
    `;
    
    reviewHTML += `
        <div class="order-total">
            <span>Total:</span>
            <span>${subtotal.toFixed(2)} Birr</span>
        </div>
    `;
    
    checkoutSummary.innerHTML = summaryHTML;
    orderReview.innerHTML = reviewHTML;
    placeOrderBtn.disabled = false;
}

function togglePaymentProof() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    const proofSection = document.getElementById('paymentProofSection');
    const proofInput = document.getElementById('paymentProof');
    
    if (paymentMethod === 'online') {
        proofSection.style.display = 'block';
        proofInput.required = true;
    } else {
        proofSection.style.display = 'none';
        proofInput.required = false;
    }
}

function setupFormValidation() {
    const form = document.getElementById('checkoutForm');
    
    form.addEventListener('submit', function(e) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            e.preventDefault();
            showModernNotification('Your cart is empty! Please add some products.', 'error');
            return false;
        }
        
        const paymentMethod = document.getElementById('paymentMethod').value;
        if (paymentMethod === 'online') {
            const proofInput = document.getElementById('paymentProof');
            const paymentLink = document.querySelector('input[name="payment_link"]').value;
            
            if (!proofInput.files[0] && !paymentLink) {
                e.preventDefault();
                showModernNotification('Please provide either payment proof or payment link for online payment.', 'error');
                return false;
            }
        }
        
        // Show loading state
        const submitBtn = document.getElementById('placeOrderBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;
        
        return true;
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>