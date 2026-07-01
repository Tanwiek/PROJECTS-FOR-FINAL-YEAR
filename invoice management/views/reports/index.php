<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card p-4 text-center">
            <h6 class="text-muted mb-2">Total Revenue</h6>
            <h3 class="text-primary mb-0"><?php echo htmlspecialchars((string)($stats['total_revenue'] ?? '0 FCFA')); ?></h3>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card p-4 text-center">
            <h6 class="text-muted mb-2">Pending Payments</h6>
            <h3 class="text-warning mb-0"><?php echo htmlspecialchars((string)($stats['pending_payments'] ?? '0 FCFA')); ?></h3>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card p-4 text-center">
            <h6 class="text-muted mb-2">Completed Projects</h6>
            <h3 class="text-success mb-0"><?php echo htmlspecialchars((string)($stats['completed_projects'] ?? 0)); ?></h3>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card p-4 text-center">
            <h6 class="text-muted mb-2">Active Projects</h6>
            <h3 class="text-info mb-0"><?php echo htmlspecialchars((string)($stats['active_projects'] ?? 0)); ?></h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card p-4">
            <h5>Graphical Analysis (Simulation)</h5>
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                <p class="text-muted mt-3">Financial and operational performance charts will be displayed here.</p>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-outline-primary"><i class="bi bi-file-earmark-pdf me-2"></i> Export to PDF</button>
                <button class="btn btn-outline-success"><i class="bi bi-file-earmark-excel me-2"></i> Export to Excel</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
