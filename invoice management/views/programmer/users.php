<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <h5>User List</h5>
            <div class="table-responsive mt-3">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                            <td><code><?php echo htmlspecialchars($u['username']); ?></code></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($u['role_name']); ?></span></td>
                            <td><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="<?php echo BASE_URL; ?>/programmer/users/delete?id=<?php echo $u['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5>Create User</h5>
            <form action="<?php echo BASE_URL; ?>/programmer/users/store" method="POST" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Create Account</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
