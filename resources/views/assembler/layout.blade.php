<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#1F2A44">
    <title>@yield('title', 'Bassani Montador')</title>
    
    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bassani-red: #DE0802;
            --bassani-red-dark: #B3211A;
            --bassani-navy: #1F2A44;
            --bassani-gray: #D2D4DA;
            --bassani-light: #F5F6FA;
        }
        
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bassani-light);
            padding-bottom: 70px;
            min-height: 100vh;
        }
        
        /* Header */
        .app-header {
            background: linear-gradient(135deg, var(--bassani-navy) 0%, #2a3a5c 100%);
            color: white;
            padding: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .app-header .greeting {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .app-header .user-name {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .app-header .avatar {
            width: 42px;
            height: 42px;
            background: var(--bassani-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        /* Quick Stats */
        .stats-container {
            display: flex;
            gap: 12px;
            padding: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .stat-card {
            flex: 0 0 auto;
            min-width: 120px;
            background: white;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .stat-card .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bassani-navy);
        }
        
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 16px 16px;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 20px 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .action-card:active {
            transform: scale(0.96);
        }
        
        .action-card .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 10px;
        }
        
        .action-card .action-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--bassani-navy);
        }
        
        /* Section Title */
        .section-title {
            padding: 16px 16px 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--bassani-navy);
        }
        
        /* Schedule Card */
        .schedule-card {
            background: white;
            border-radius: 16px;
            margin: 0 16px 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid var(--bassani-red);
        }
        
        .schedule-card .schedule-time {
            font-size: 0.8rem;
            color: var(--bassani-red);
            font-weight: 600;
        }
        
        .schedule-card .schedule-client {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--bassani-navy);
            margin: 4px 0;
        }
        
        .schedule-card .schedule-address {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .schedule-card .schedule-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-started { background: #cce5ff; color: #004085; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
            z-index: 1000;
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #6c757d;
            padding: 4px 12px;
            font-size: 0.65rem;
            transition: color 0.2s;
        }
        
        .nav-item.active {
            color: var(--bassani-red);
        }
        
        .nav-item i {
            font-size: 1.5rem;
            margin-bottom: 2px;
        }
        
        .nav-item.nav-home {
            position: relative;
            top: -15px;
        }
        
        .nav-item.nav-home i {
            width: 50px;
            height: 50px;
            background: var(--bassani-red);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(222, 8, 2, 0.3);
        }
        
        .nav-item.nav-home.active i {
            background: var(--bassani-red-dark);
        }
        
        /* Expense Card */
        .expense-card {
            background: white;
            border-radius: 16px;
            margin: 0 16px 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .expense-card .expense-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        
        .expense-card .expense-details {
            flex: 1;
            min-width: 0;
        }
        
        .expense-card .expense-category {
            font-weight: 600;
            color: var(--bassani-navy);
            font-size: 0.9rem;
        }
        
        .expense-card .expense-date {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .expense-card .expense-amount {
            font-weight: 700;
            color: var(--bassani-red);
            font-size: 1rem;
        }
        
        .expense-card .expense-status {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        /* Form Styles */
        .form-card {
            background: white;
            border-radius: 16px;
            margin: 0 16px 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .form-card .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--bassani-navy);
        }
        
        .form-card .form-control,
        .form-card .form-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
            font-size: 0.95rem;
        }
        
        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: var(--bassani-red);
            box-shadow: 0 0 0 3px rgba(222, 8, 2, 0.1);
        }
        
        /* Category Buttons */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .category-btn {
            background: #f5f6fa;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px 8px;
            text-align: center;
            transition: all 0.2s;
        }
        
        .category-btn.selected {
            background: #fff0f0;
            border-color: var(--bassani-red);
        }
        
        .category-btn i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 4px;
        }
        
        .category-btn span {
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, var(--bassani-red) 0%, var(--bassani-red-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            color: white;
            box-shadow: 0 4px 12px rgba(222, 8, 2, 0.3);
        }
        
        .btn-submit:active {
            transform: scale(0.98);
        }
        
        /* Page Title */
        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--bassani-navy);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-title i {
            font-size: 1.3rem;
            color: var(--bassani-red);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--bassani-gray);
            margin-bottom: 12px;
        }
        
        .empty-state p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Toast */
        .toast-container {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
        }
        
        .custom-toast {
            background: white;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 280px;
        }
        
        .custom-toast.success { border-left: 4px solid #28a745; }
        .custom-toast.error { border-left: 4px solid #dc3545; }
        .custom-toast.warning { border-left: 4px solid #ffc107; }
    </style>
</head>
<body>
    {{-- Toast Container --}}
    <div class="toast-container" id="toastContainer"></div>
    
    @yield('content')
    
    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        <a href="{{ url('/assembler/home') }}" class="nav-item {{ request()->is('assembler/home') ? 'active' : '' }}">
            <i class="bx bx-home"></i>
            <span>Início</span>
        </a>
        <a href="{{ url('/my-schedule') }}" class="nav-item {{ request()->is('my-schedule') ? 'active' : '' }}">
            <i class="bx bx-calendar"></i>
            <span>Agenda</span>
        </a>
        <a href="{{ url('/assembler/home') }}" class="nav-item nav-home {{ request()->is('assembler/home*') ? 'active' : '' }}">
            <i class="bx bx-plus"></i>
        </a>
        <a href="{{ url('/assembler/expenses') }}" class="nav-item {{ request()->is('assembler/expenses*') ? 'active' : '' }}">
            <i class="bx bx-receipt"></i>
            <span>Despesas</span>
        </a>
        <a href="{{ url('/assembler/profile') }}" class="nav-item {{ request()->is('assembler/profile') ? 'active' : '' }}">
            <i class="bx bx-user"></i>
            <span>Perfil</span>
        </a>
    </nav>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const icons = {
                success: 'bx-check-circle',
                error: 'bx-x-circle',
                warning: 'bx-error-circle',
                info: 'bx-info-circle'
            };
            
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;
            toast.innerHTML = `
                <i class="bx ${icons[type] || icons.info}" style="color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8'}"></i>
                <span style="font-size: 0.9rem;">${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Show session messages as toasts
        @if(session('success'))
            showToast('{{ session("success") }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session("error") }}', 'error');
        @endif
        @if(session('warning'))
            showToast('{{ session("warning") }}', 'warning');
        @endif
    </script>
    
    @yield('scripts')
</body>
</html>
