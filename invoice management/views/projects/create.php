<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h4 class="mb-4">Create New Project</h4>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/projects/store" method="POST">
                <div class="mb-3">
                    <label class="form-label">Project Code</label>
                    <input type="text" name="project_code" class="form-control" placeholder="ex: PRJ-2024-001" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Project Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Descriptive project title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Associated Tender (Optional)</label>
                    <select name="tender_id" class="form-select">
                        <option value="">-- None --</option>
                        <?php foreach ($tenders as $tender): ?>
                            <option value="<?php echo $tender['id']; ?>">
                                <?php echo htmlspecialchars($tender['reference_number'] . ' - ' . $tender['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo BASE_URL; ?>/projects" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
