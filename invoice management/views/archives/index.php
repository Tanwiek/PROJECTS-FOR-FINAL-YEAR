<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Centralized Archives</h4>
    <div class="d-flex gap-2">
        <form class="d-flex" action="<?php echo BASE_URL; ?>/archives" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Project name or code..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </div>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Project Code</th>
                    <th>Title</th>
                    <th>Archived Date</th>
                    <th>Archived By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projects)): ?>
                    <tr><td colspan="5" class="text-center py-4">No archived projects found.</td></tr>
                <?php else: ?>
                    <?php foreach ($projects as $prj): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($prj['project_code']); ?></strong></td>
                        <td><?php echo htmlspecialchars($prj['title']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($prj['archived_at'])); ?></td>
                        <td><?php echo htmlspecialchars($prj['archived_by_name'] ?? 'System'); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo BASE_URL; ?>/projects/show?id=<?php echo $prj['id']; ?>" class="btn btn-sm btn-outline-info" title="Consult (Read Only)">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/archives/download?id=<?php echo $prj['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Download ZIP Pack">
                                    <i class="bi bi-download"></i>
                                </a>
                                <?php if (($_SESSION['role'] ?? '') === 'Directeur Général'): ?>
                                <a href="<?php echo BASE_URL; ?>/archives/unarchive?id=<?php echo $prj['id']; ?>" class="btn btn-sm btn-outline-warning" title="Restore" onclick="return confirm('Do you want to restore this project?')">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
