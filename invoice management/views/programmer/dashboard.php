<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card p-4 text-center bg-primary text-white">
            <h6 class="mb-2">Total Users</h6>
            <h3 class="mb-0"><?php echo htmlspecialchars((string)$user_count); ?></h3>
            <a href="<?php echo BASE_URL; ?>/programmer/users" class="text-white-50 mt-2 d-block small">Manage accounts →</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center bg-danger text-white">
            <h6 class="mb-2">System Errors (24h)</h6>
            <h3 class="mb-0">0</h3>
            <a href="<?php echo BASE_URL; ?>/programmer/logs" class="text-white-50 mt-2 d-block small">View logs →</a>
        </div>
    </div>
</div>

<div class="card p-4">
    <h5>System Status</h5>
    <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-light rounded mb-2">
            Database (MySQL)
            <span class="badge bg-success">Connected</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-light rounded">
            Storage Space
            <span class="badge bg-info">Healthy</span>
        </li>
    </ul>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
