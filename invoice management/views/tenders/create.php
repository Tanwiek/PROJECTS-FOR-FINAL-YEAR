<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4 mx-auto" style="max-width: 800px;">
    <h4 class="mb-4">Create New Tender</h4>
    
    <form action="<?php echo BASE_URL; ?>/tenders/store" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="ex: AO-2026-001" required>
            </div>
            <div class="col-md-6">
                <label for="deadline" class="form-label">Submission Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" required>
            </div>
            <div class="col-12">
                <label for="title" class="form-label">Tender Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="col-12">
                <label for="client_name" class="form-label">Client Name</label>
                <input type="text" class="form-control" id="client_name" name="client_name" required>
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description / Details</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>
            <div class="col-md-6">
                <label for="department" class="form-label">Assigned Department</label>
                <select class="form-select" id="department" name="department">
                    <option value="Commercial">Commercial</option>
                    <option value="Technique">Technical</option>
                    <option value="Administratif">Administrative</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="documents" class="form-label">Documents (PDF, DOC)</label>
                <input type="file" class="form-control" id="documents" name="documents[]" multiple>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="<?php echo BASE_URL; ?>/tenders" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Tender</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
