<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'ALWAYS TECHNOLOGIES'; ?></title>
    <style>
        /* Premium ALWAYS TECHNOLOGIES Dashboard (Total Offline Support) */
        :root { 
            --bs-primary: #1a2a6c; --bs-success: #198754; --bs-danger: #dc3545; --bs-warning: #ffc107;
            --main-bg: #f5f7fb; --sidebar-bg: #1a2a6c;
        }
        body { margin: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; font-size: 0.95rem; line-height: 1.6; color: #333; background-color: var(--main-bg); overflow-x: hidden; }
        *, ::after, ::before { box-sizing: border-box; }
        
        /* Robust Flex Layout */
        .app-wrapper { display: flex; min-height: 100vh; width: 100%; }
        .sidebar { width: 280px; flex-shrink: 0; background-color: var(--sidebar-bg); color: white; padding: 2.5rem 0; z-index: 1000; transition: all 0.3s; }
        .main-content { flex-grow: 1; min-width: 0; padding: 0; background-color: var(--main-bg); transition: 0.3s; }
        .content-padding { padding: 2.5rem; }

        /* Grid System with Gutters */
        .row { display: flex; flex-wrap: wrap; margin-top: calc(-1 * var(--bs-gutter-y, 0)); margin-right: calc(-0.5 * var(--bs-gutter-x, 1.5rem)); margin-left: calc(-0.5 * var(--bs-gutter-x, 1.5rem)); }
        .row > * { flex-shrink: 0; width: 100%; max-width: 100%; padding-right: calc(var(--bs-gutter-x, 1.5rem) * 0.5); padding-left: calc(var(--bs-gutter-x, 1.5rem) * 0.5); margin-top: var(--bs-gutter-y, 0); }
        
        .g-0 { --bs-gutter-x: 0; --bs-gutter-y: 0; }
        .g-1 { --bs-gutter-x: 0.25rem; --bs-gutter-y: 0.25rem; }
        .g-2 { --bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem; }
        .g-3 { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }
        .g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
        .g-5 { --bs-gutter-x: 3rem; --bs-gutter-y: 3rem; }

        .col-12 { flex: 0 0 auto; width: 100%; }
        .col-md-6 { flex: 0 0 auto; width: 50%; }
        .col-xl-3 { flex: 0 0 auto; width: 25%; }
        .col-lg-8 { flex: 0 0 auto; width: 66.6666%; }
        .col-lg-4 { flex: 0 0 auto; width: 33.3333%; }
        
        @media (max-width: 992px) { 
            .col-md-6, .col-xl-3, .col-lg-8, .col-lg-4 { flex: 0 0 auto; width: 100%; } 
            .sidebar { position: fixed; left: -280px; }
            .sidebar.active { left: 0; }
        }

        /* Component Styles */
        .card { display: flex; flex-direction: column; background: #fff; border: none; border-radius: 1.25rem; box-shadow: 0 4px 25px rgba(0,0,0,0.05); margin-bottom: 2rem; position: relative; transition: transform 0.2s; }
        .card-body, .p-3, .p-4 { padding: 1.75rem !important; }
        .d-flex { display: flex !important; }
        .align-items-center { align-items: center !important; }
        .justify-content-between { justify-content: space-between !important; }
        
        /* Stats Styles */
        .rounded-circle { border-radius: 50% !important; display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; flex-shrink: 0; }
        .bg-primary.bg-opacity-10 { background-color: rgba(26, 42, 108, 0.1) !important; color: var(--bs-primary); }
        .bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1) !important; color: var(--bs-success); }
        .bg-warning.bg-opacity-10 { background-color: rgba(255, 193, 7, 0.1) !important; color: var(--bs-warning); }
        .bg-danger.bg-opacity-10 { background-color: rgba(220, 53, 69, 0.1) !important; color: var(--bs-danger); }
        
        .text-muted { color: #8e98a8 !important; font-size: 0.85rem; margin-bottom: 0.25rem; display: block; }
        h4 { margin: 0; color: #2d3748; font-weight: 700; font-size: 1.25rem; }
        h5 { margin: 0 0 1.5rem 0; color: #2d3748; font-weight: 600; font-size: 1.1rem; }
        
        /* Sidebar Links */
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 0.85rem 2rem; text-decoration: none; display: flex; align-items: center; border-left: 4px solid transparent; transition: 0.2s; font-weight: 500; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); border-left-color: #fdbb2d; }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.15); }
        .sidebar-brand { font-size: 1.25rem; font-weight: 800; padding: 0 2rem 2.5rem; color: #fff; display: block; letter-spacing: 1px; }

        /* Navbar */
        .navbar { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        
        /* Badges & Tables */
        .badge { padding: 0.4em 0.8em; font-size: 0.75rem; font-weight: 600; border-radius: 0.5rem; display: inline-block; text-transform: capitalize; }
        .bg-success { background-color: #d1fae5 !important; color: #065f46 !important; }
        .bg-danger { background-color: #fee2e2 !important; color: #991b1b !important; }
        .bg-warning { background-color: #fef3c7 !important; color: #92400e !important; }
        
        .table { width: 100%; border-collapse: collapse; }
        .table th { background-color: #f8fafc; color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; padding: 1rem; border-bottom: 2px solid #edf2f7; text-align: left; }
        .table td { padding: 1rem; border-bottom: 1px solid #edf2f7; color: #4a5568; }
        .table tr:hover td { background-color: #f8fafc; }

        /* Font Sizes */
        .fs-1 { font-size: 2.5rem !important; }
        .fs-2 { font-size: 2rem !important; }
        .fs-3 { font-size: 1.75rem !important; }
        .fs-4 { font-size: 1.5rem !important; }
        .fs-5 { font-size: 1.25rem !important; }
        .fs-6 { font-size: 1rem !important; }

        .btn { padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer; text-decoration: none; border: 1px solid #e2e8f0; background: #fff; color: #4a5568; display: inline-flex; align-items: center; transition: 0.2s; }
        .btn-outline-danger { border-color: #fed7d7; color: #e53e3e; }
        .btn-outline-danger:hover { background: #fee2e2; }
        
        /* Icons (Unicode) */
        .bi { font-size: 1.25rem; margin-right: 0.75rem; }
        .bi-speedometer2::before { content: "📊"; }
        .bi-file-earmark-text::before { content: "📝"; }
        .bi-folder2::before { content: "📂"; }
        .bi-receipt::before { content: "🧾"; }
        .bi-card-checklist::before { content: "📋"; }
        .bi-graph-up::before { content: "📈"; }
        .bi-truck::before { content: "🚚"; }
        .bi-archive::before { content: "📦"; }
        .bi-shield-lock::before { content: "🛡️"; }
        .bi-wallet2::before { content: "💰"; }
        .bi-clock-history::before { content: "⏳"; }
        .bi-exclamation-triangle::before { content: "⚠️"; }
        .bi-plus-lg::before { content: "➕"; }
        .bi-eye::before { content: "👁️"; }
        .bi-trash::before { content: "🗑️"; }

        /* Form Controls */
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568; }
        .form-control, .form-select, textarea.form-control { 
            display: block; width: 100%; padding: 0.6rem 1rem; font-size: 0.95rem; line-height: 1.5; color: #2d3748; 
            background-color: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; transition: border-color 0.2s; 
            font-family: inherit;
        }
        .form-control:focus, .form-select:focus, textarea.form-control:focus { border-color: var(--bs-primary); outline: 0; box-shadow: 0 0 0 3px rgba(26, 42, 108, 0.1); }
        .form-select { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 16px 12px; appearance: none; }
        
        /* Layout Utilities */
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        .gap-3 { gap: 1rem !important; }
        .mx-auto { margin-left: auto !important; margin-right: auto !important; }
        .w-100 { width: 100% !important; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; }

        /* Dropdowns */
        .dropdown { position: relative; }
        .dropdown-menu { 
            display: none; position: absolute; top: 100%; right: 0; min-width: 10rem; padding: 0.5rem 0; margin: 0.125rem 0 0; 
            background-color: #fff; border: 1px solid rgba(0,0,0,0.15); border-radius: 0.5rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.175); z-index: 1000; 
        }
        .dropdown-menu.show { display: block !important; }
        .dropdown-item { display: block; width: 100%; padding: 0.5rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; text-decoration: none; white-space: nowrap; background-color: transparent; border: 0; }
        .dropdown-item:hover { background-color: #f8f9fa; color: var(--bs-primary); }
        .dropdown-divider { height: 1px; margin: 0.5rem 0; overflow: hidden; background-color: #e9ecef; }

        /* Alerts Dismissible */
        .alert-dismissible { position: relative; padding-right: 3rem; }
        .btn-close { 
            position: absolute; top: 0; right: 0; padding: 1.25rem 1rem; border: none; background: transparent; cursor: pointer; opacity: 0.5; transition: 0.2s; 
        }
        .btn-close:hover { opacity: 1; }
        .btn-close::before { content: "✕"; font-size: 1.2rem; font-weight: bold; }

        /* Small form variants */
        .form-control-sm, .form-select-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.3rem; }
        .border-top { border-top: 1px solid #edf2f7 !important; }
        .pt-3 { padding-top: 1rem !important; }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <span class="sidebar-brand">ALWAYS TECH</span>
            <div class="nav flex-column">
                <?php 
                $role = $_SESSION['role'] ?? '';
                $isAdmin = ($role === 'Directeur Général');
                $isComptable = ($role === 'Comptable');
                $isAssistant = in_array($role, ['Assistante de direction', 'Responsable commerciale', 'Business Developer']);
                $isLogistique = ($role === 'Responsable Logistique');
                $isProg = ($role === 'Programmeur');
                
                if ($isProg): ?>
                    <a class="nav-link active" href="<?php echo BASE_URL; ?>/programmer">
                        <i class="bi bi-shield-lock"></i> Programmer Console
                    </a>
                <?php else: ?>
                    <a class="nav-link <?php echo ($title ?? '') === 'Dashboard' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>

                    <?php if ($isAdmin || $isAssistant): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/tenders">
                        <i class="bi bi-file-earmark-text"></i> Tenders
                    </a>
                    <?php endif; ?>

                    <?php if ($isAdmin || $isAssistant || $isComptable): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/projects">
                        <i class="bi bi-folder2"></i> Projects / Files
                    </a>
                    <?php endif; ?>

                    <?php if ($isAdmin || $isComptable): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/invoices">
                        <i class="bi bi-receipt"></i> Invoicing
                    </a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/payments">
                        <i class="bi bi-wallet2"></i> Payments
                    </a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/reports">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                    <?php endif; ?>

                    <?php if ($isAdmin || $isLogistique): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/suppliers">
                        <i class="bi bi-truck"></i> Suppliers
                    </a>
                    <?php endif; ?>

                    <a class="nav-link" href="<?php echo BASE_URL; ?>/archives">
                        <i class="bi bi-archive"></i> Archives
                    </a>

                    <?php if ($isAdmin): ?>
                    <a class="nav-link <?php echo ($title ?? '') === 'User Management' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/programmer/users">
                        <i class="bi bi-shield-lock"></i> Manage Users
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar">
                <h4 class="mb-0"><?php echo $title ?? 'Dashboard'; ?></h4>
                <div class="d-flex align-items-center">
                    <?php
                    $roleMap = [
                        'Directeur Général' => 'General Manager',
                        'Programmeur' => 'Developer',
                        'Comptable' => 'Accountant',
                        'Assistante de direction' => 'Executive Assistant',
                        'Responsable commerciale' => 'Sales Manager',
                        'Business Developer' => 'Business Developer',
                        'Responsable Logistique' => 'Logistics Manager'
                    ];
                    $displayRole = $roleMap[$_SESSION['role'] ?? ''] ?? ($_SESSION['role'] ?? 'Role');
                    ?>
                    <span class="text-muted me-3"><?php echo $_SESSION['full_name'] ?? 'User'; ?> (<?php echo $displayRole; ?>)</span>
                    <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-outline-danger btn-sm">Log out</a>
                </div>
            </nav>
            <!-- Page Content -->
            <div class="content-padding">
