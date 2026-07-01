<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Activity & Error Logs</h5>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-light">
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></td>
                    <td><strong><?php echo htmlspecialchars($log['full_name'] ?? 'System'); ?></strong></td>
                    <td>
                        <span class="badge <?php echo str_contains($log['action'], 'Error') ? 'bg-danger' : 'bg-info'; ?>">
                            <?php echo htmlspecialchars($log['action']); ?>
                        </span>
                    </td>
                    <td><small class="text-muted"><?php echo htmlspecialchars($log['details']); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
