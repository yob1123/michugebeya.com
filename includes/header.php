<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michu Gebeya - Premium Children & Infant Clothing</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Michu Gebeya Logo" class="logo">
                    <span>ምቹ ገበያ</span>
                </a>
            </div>
            
            <div class="nav-search">
                <form class="search-form">
                    <input type="text" placeholder="Search products..." class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <div class="nav-menu">
                <a href="index.php" class="nav-link">Home</a>
                <a href="products.php" class="nav-link">Products</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="contact.php" class="nav-link">Contact</a>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="dashboard.php" class="nav-link admin-link">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="profile.php" class="nav-link">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="nav-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                <?php endif; ?>
                
                <a href="cart.php" class="nav-link cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
            
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <style>
    .nav-search {
        flex: 1;
        max-width: 400px;
        margin: 0 2rem;
    }

    .search-form {
        position: relative;
        display: flex;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E2E8F0;
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: var(--white);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--light-gray);
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }

    .search-btn:hover {
        color: var(--primary-orange);
    }

    .admin-link {
        background: var(--gradient-primary);
        color: white !important;
        padding: 0.5rem 1rem !important;
        border-radius: var(--border-radius);
    }

    .admin-link:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .admin-link::after {
        display: none;
    }

    @media (max-width: 1024px) {
        .nav-search {
            display: none;
        }
    }
    </style>