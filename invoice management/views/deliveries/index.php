<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Deliveries & Deployments</h4>
    <a href="<?php echo BASE_URL; ?>/deliveries/create" class="btn btn-primary">
        <i class="bi bi-truck me-2"></i> Record Delivery
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Delivery No.</th>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Signed Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center py-4">No delivery recorded.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
