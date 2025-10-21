<?php
require_once 'includes/header.php';
?>

<div class="cart-container">
    <h1>Shopping Cart</h1>
    
    <div id="cartItems" class="cart-items">
        <!-- Cart items will be loaded via JavaScript -->
    </div>
    
    <div class="cart-summary">
        <div class="summary-card">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span id="subtotal">0 Birr</span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>Free</span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span id="total">0 Birr</span>
            </div>
            
            <a href="checkout.php" class="btn btn-primary" id="checkoutBtn">Proceed to Checkout</a>
        </div>
    </div>
</div>

<style>
.cart-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: var(--white);
    border-radius: 10px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cart-item-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.cart-item-details {
    flex-grow: 1;
}

.cart-item-price {
    font-weight: bold;
    color: var(--primary-color);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
}

.quantity-btn {
    background: var(--light-blue);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
}

.remove-btn {
    background: #ff4444;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
}

.summary-card {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}

.summary-row.total {
    font-weight: bold;
    font-size: 1.2rem;
    border-bottom: none;
}

.empty-cart {
    text-align: center;
    padding: 3rem;
}

.empty-cart a {
    display: inline-block;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
});

function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cartItems');
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkoutBtn');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <h3>Your cart is empty</h3>
                <p>Browse our products and add some items to your cart!</p>
                <a href="products.php" class="btn">Continue Shopping</a>
            </div>
        `;
        subtotalElement.textContent = '0 Birr';
        totalElement.textContent = '0 Birr';
        checkoutBtn.style.display = 'none';
        return;
    }
    
    let subtotal = 0;
    let cartHTML = '';
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        cartHTML += `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="cart-item-details">
                    <h3>${item.name}</h3>
                    <p class="cart-item-price">${item.price} Birr</p>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                </div>
                <button class="remove-btn" onclick="removeFromCart(${index})">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
    });
    
    cartItems.innerHTML = cartHTML;
    subtotalElement.textContent = subtotal + ' Birr';
    totalElement.textContent = subtotal + ' Birr';
    checkoutBtn.style.display = 'block';
}

function updateQuantity(index, change) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart[index]) {
        cart[index].quantity += change;
        
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        loadCartItems();
    }
}

function removeFromCart(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    loadCartItems();
    showNotification('Item removed from cart');
}
</script>

<?php require_once 'includes/footer.php'; ?>