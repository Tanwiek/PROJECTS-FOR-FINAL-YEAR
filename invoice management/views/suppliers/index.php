<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Supplier Directory</h4>
    <a href="<?php echo BASE_URL; ?>/suppliers/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Add Supplier
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">No supplier found.</td>
                    </tr>
                <?php else: ?>
                    <!-- Loop through suppliers here -->
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
