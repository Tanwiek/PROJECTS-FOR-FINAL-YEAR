<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4 mx-auto" style="max-width: 700px;">
    <h4 class="mb-4">Record New Payment</h4>
    
    <form action="<?php echo BASE_URL; ?>/payments/store" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="invoice_id" class="form-label">Concerned Invoice</label>
                <select class="form-select" id="invoice_id" name="invoice_id" required>
                    <option value="">Select an invoice...</option>
                    <?php foreach ($invoices as $inv): ?>
                        <option value="<?php echo $inv['id']; ?>">
                            <?php echo htmlspecialchars($inv['invoice_number']); ?> - <?php echo htmlspecialchars($inv['project_title']); ?> (<?php echo number_format($inv['amount'], 0, ',', ' '); ?> FCFA)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="amount" class="form-label">Amount Paid (FCFA)</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="col-md-6">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Cash">Cash</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-12">
                <label for="reference" class="form-label">Reference / Transaction No.</label>
                <input type="text" class="form-control" id="reference" name="reference" placeholder="ex: VIR-849302">
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="<?php echo BASE_URL; ?>/payments" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Record Payment</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
