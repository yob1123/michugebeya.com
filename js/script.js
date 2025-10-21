// Modern JavaScript with enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Navbar scroll effect
    initNavbarScroll();
    
    // Mobile menu
    initMobileMenu();
    
    // Cart functionality
    initCart();
    
    // Smooth scrolling
    initSmoothScroll();
    
    // Animation on scroll
    initScrollAnimations();
    
    // Product interactions
    initProductInteractions();
    
    // Form enhancements
    initFormEnhancements();
}

function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScrollY = window.scrollY;
    });
}

function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // Animate hamburger to X
            this.style.transform = this.classList.contains('active') ? 'rotate(90deg)' : 'rotate(0deg)';
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            hamburger.style.transform = 'rotate(0deg)';
        }
    });
}

function initCart() {
    updateCartCount();
    
    // Cart animation
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                window.location.href = 'cart.php';
            }, 150);
        });
    }
}

function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.product-card, .stat-card, .feature-card').forEach(el => {
        observer.observe(el);
    });
}

function initProductInteractions() {
    // Product image hover effect
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add to cart with animation
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const price = this.dataset.price;
            const image = this.dataset.image;
            
            addToCartWithAnimation(productId, productName, price, image, this);
        });
    });
}

function initFormEnhancements() {
    // Add focus effects to form elements
    document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
}

function addToCartWithAnimation(productId, productName, price, image, button) {
    // Create flying element animation
    const buttonRect = button.getBoundingClientRect();
    const cartIcon = document.querySelector('.cart-icon');
    const cartRect = cartIcon.getBoundingClientRect();
    
    const flyingElement = document.createElement('div');
    flyingElement.style.cssText = `
        position: fixed;
        width: 20px;
        height: 20px;
        background: var(--primary-orange);
        border-radius: 50%;
        pointer-events: none;
        z-index: 10000;
        left: ${buttonRect.left + buttonRect.width/2}px;
        top: ${buttonRect.top + buttonRect.height/2}px;
        transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    `;
    
    document.body.appendChild(flyingElement);
    
    // Animate to cart
    setTimeout(() => {
        flyingElement.style.left = `${cartRect.left + cartRect.width/2}px`;
        flyingElement.style.top = `${cartRect.top + cartRect.height/2}px`;
        flyingElement.style.transform = 'scale(0.1)';
        flyingElement.style.opacity = '0.5';
    }, 50);
    
    // Remove element and update cart
    setTimeout(() => {
        flyingElement.remove();
        addToCart(productId, productName, price, image);
        
        // Cart icon bounce effect
        cartIcon.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1)';
        }, 300);
        
        showModernNotification('Product added to cart!', 'success');
    }, 800);
}

function showModernNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `modern-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <div class="notification-progress"></div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        border-left: 4px solid ${getNotificationColor(type)};
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10000;
        min-width: 300px;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
    // Progress bar animation
    const progress = notification.querySelector('.notification-progress');
    progress.style.cssText = `
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: ${getNotificationColor(type)};
        width: 100%;
        transform: scaleX(1);
        transform-origin: left;
        transition: transform 3s linear;
    `;
    
    setTimeout(() => {
        progress.style.transform = 'scaleX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function getNotificationColor(type) {
    const colors = {
        success: '#10B981',
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#4A6CF7'
    };
    return colors[type] || '#4A6CF7';
}

// Enhanced cart functions
function addToCart(productId, productName, price, image) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: parseFloat(price),
            image: image,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = document.querySelector('.cart-count');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// Image lazy loading
function initLazyLoading() {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Search functionality
function initSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let debounceTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });
    }
}

function performSearch(query) {
    // Implement search logic here
    console.log('Searching for:', query);
}

// Price filter range slider
function initPriceRangeSlider() {
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            const value = this.value;
            priceValue.textContent = `Up to ${value} Birr`;
            
            // Update filter results
            filterProductsByPrice(value);
        });
    }
}

function filterProductsByPrice(maxPrice) {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const productPrice = parseFloat(product.dataset.price);
        if (productPrice <= maxPrice) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Quick view modal
function initQuickView() {
    document.querySelectorAll('.quick-view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            showQuickView(productId);
        });
    });
}

function showQuickView(productId) {
    // Fetch product data and show in modal
    fetch(`api/product.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            // Create and show modal with product details
            showProductModal(product);
        })
        .catch(error => {
            console.error('Error loading product:', error);
        });
}

function showProductModal(product) {
    // Implement modal display logic
    console.log('Show product modal:', product);
}