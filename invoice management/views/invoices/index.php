<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Invoice Management</h4>
    <a href="<?php echo BASE_URL; ?>/invoices/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Create Invoice
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Invoice No.</th>
                    <th>Project</th>
                    <th>Amount (FCFA)</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">No invoice found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($inv['invoice_number']); ?></strong></td>
                        <td><?php echo htmlspecialchars($inv['project_title'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format($inv['amount'], 0, ',', ' '); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($inv['issue_date'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($inv['due_date'])); ?></td>
                        <td>
                            <?php 
                            $badgeClass = 'bg-secondary';
                            if ($inv['status'] === 'Paid') $badgeClass = 'bg-success';
                            if ($inv['status'] === 'Sent') $badgeClass = 'bg-primary';
                            if ($inv['status'] === 'Overdue') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($inv['status']); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/invoices/show?id=<?php echo $inv['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
