<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Tender Management</h4>
    <a href="<?php echo BASE_URL; ?>/tenders/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> New Tender
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Reference</th>
                    <th>Title</th>
                    <th>Client</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tenders)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No tender found.</td>
                    </tr>
                <?php else: ?>
                    <!-- Loop through tenders here -->
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
