<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Purchase Orders</h4>
    <a href="<?php echo BASE_URL; ?>/orders/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> New Purchase Order
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Order No.</th>
                    <th>Project</th>
                    <th>Supplier</th>
                    <th>Amount (FCFA)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center py-4">No purchase order found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
