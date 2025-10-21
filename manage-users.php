<?php
require_once 'includes/auth.php';
redirectIfNotAdmin();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['make_admin'])) {
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE users SET user_type = 'admin' WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['success'] = "User promoted to admin successfully!";
    }
    
    if (isset($_POST['remove_admin'])) {
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE users SET user_type = 'customer' WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['success'] = "Admin privileges removed successfully!";
    }
}

// Get all users
$stmt = $pdo->query("SELECT id, username, email, user_type, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="dashboard-container">
    <h1>Manage Users</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="admin-card">
        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="role-badge <?php echo $user['user_type']; ?>">
                                <?php echo ucfirst($user['user_type']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['user_type'] === 'customer'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="make_admin" class="btn btn-secondary btn-sm">
                                    Make Admin
                                </button>
                            </form>
                            <?php elseif ($user['user_type'] === 'admin' && $user['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="remove_admin" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Remove admin privileges from this user?')">
                                    Remove Admin
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-muted">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.role-badge.admin {
    background: var(--primary-color);
    color: white;
}

.role-badge.customer {
    background: var(--light-blue);
    color: var(--text-color);
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
}

.text-muted {
    color: #999;
    font-style: italic;
}
</style>

<?php require_once 'includes/footer.php'; ?>