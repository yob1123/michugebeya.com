<?php
require_once 'includes/auth.php';
redirectIfNotAdmin();

// Get all messages
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once 'includes/header.php'; ?>

<div class="dashboard-container">
    <h1>Customer Messages</h1>
    
    <div class="admin-card">
        <?php if (empty($messages)): ?>
            <p class="no-data">No messages found.</p>
        <?php else: ?>
            <div class="messages-list">
                <?php foreach ($messages as $message): ?>
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-sender">
                            <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                            <span><?php echo htmlspecialchars($message['email']); ?></span>
                        </div>
                        <div class="message-date">
                            <?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                        </div>
                    </div>
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.messages-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.message-item {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 1.5rem;
    background: var(--white);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.message-sender {
    display: flex;
    flex-direction: column;
}

.message-sender strong {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.message-sender span {
    color: #666;
    font-size: 0.9rem;
}

.message-date {
    color: #999;
    font-size: 0.9rem;
}

.message-content {
    line-height: 1.6;
    color: #333;
}

@media (max-width: 768px) {
    .message-header {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>