<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4 mx-auto" style="max-width: 800px;">
    <h4 class="mb-4">Submit Offer <?php echo htmlspecialchars((string)($type ?? '')); ?></h4>
    <p class="text-muted">Project: ALWAYS Network Installation</p>
    
    <form action="<?php echo BASE_URL; ?>/offers/store" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars((string)($project_id ?? '')); ?>">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars((string)($type ?? '')); ?>">

        <div class="mb-3">
            <label for="offer_title" class="form-label">Offer Title</label>
            <input type="text" class="form-control" id="offer_title" name="offer_title" required>
        </div>

        <div class="mb-3">
            <label for="offer_file" class="form-label">Offer Document (PDF, DOC)</label>
            <input type="file" class="form-control" id="offer_file" name="offer_file" required>
        </div>

        <?php if ($type === 'fin'): ?>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Proposed Amount (FCFA)</label>
            <input type="number" class="form-control" id="total_amount" name="total_amount" required>
        </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes / Comments</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="<?php echo BASE_URL; ?>/projects/show?id=<?php echo $project_id; ?>" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Offer</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
