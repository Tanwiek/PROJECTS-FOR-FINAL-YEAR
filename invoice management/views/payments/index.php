<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Payment Tracking</h4>
    <a href="<?php echo BASE_URL; ?>/payments/record" class="btn btn-primary">
        <i class="bi bi-card-checklist me-2"></i> Record Payment
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Invoice No.</th>
                    <th>Amount (FCFA)</th>
                    <th>Payment Method</th>
                    <th>Reference</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No payment recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($p['payment_date'])); ?></td>
                        <td>
                            <span class="fw-bold"><?php echo htmlspecialchars($p['invoice_number']); ?></span>
                            <div class="small text-muted"><?php echo htmlspecialchars($p['project_title']); ?></div>
                        </td>
                        <td class="fw-bold"><?php echo number_format($p['amount'], 0, ',', ' '); ?> FCFA</td>
                        <td>
                            <?php 
                            $methods = ['Transfer' => 'Transfer', 'Cheque' => 'Cheque', 'Cash' => 'Cash', 'Other' => 'Other'];
                            echo $methods[$p['payment_method']] ?? $p['payment_method'];
                            ?>
                        </td>
                        <td><small><?php echo htmlspecialchars($p['reference']); ?></small></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/payments/receipt?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-printer me-1"></i> Receipt
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
