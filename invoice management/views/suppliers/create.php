<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4 mx-auto" style="max-width: 700px;">
    <h4 class="mb-4">Add New Supplier</h4>
    
    <form action="<?php echo BASE_URL; ?>/suppliers/store" method="POST">
        <div class="row g-3">
            <div class="col-12">
                <label for="name" class="form-label">Supplier / Company Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="col-12">
                <label for="address" class="form-label">Physical Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="<?php echo BASE_URL; ?>/suppliers" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Supplier</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
