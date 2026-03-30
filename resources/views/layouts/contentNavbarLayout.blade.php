@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@extends('layouts/commonMaster')

@php
/* Display elements */
$contentNavbar = $contentNavbar ?? true;
$containerNav = $containerNav ?? 'container-xxl';
$isNavbar = false;
$isMenu = $isMenu ?? true;
$isFlex = $isFlex ?? false;
$isFooter = $isFooter ?? true;
$customizerHidden = $customizerHidden ?? '';

/* HTML Classes */
$navbarDetached = 'navbar-detached';
$menuFixed = isset($configData['menuFixed']) ? $configData['menuFixed'] : '';
if (isset($navbarType)) {
  $configData['navbarType'] = $navbarType;
}
$navbarType = isset($configData['navbarType']) ? $configData['navbarType'] : '';
$footerFixed = isset($configData['footerFixed']) ? $configData['footerFixed'] : '';
$menuCollapsed = isset($configData['menuCollapsed']) ? $configData['menuCollapsed'] : '';

/* Content classes */
$container = ($container ?? 'container-xxl');
@endphp

@section('layoutContent')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        {{-- Desktop Sidebar Menu - Hidden on Mobile --}}
        @if ($isMenu)
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme d-none d-lg-block">
            @include('layouts.sections.menu-content')
        </aside>
        @endif

        {{-- Mobile Menu Overlay --}}
        <div id="mobile-overlay" class="mobile-overlay"></div>

        {{-- Mobile Sidebar Menu --}}
        @if ($isMenu)
        <aside id="mobile-menu" class="mobile-menu">
            @include('layouts.sections.menu-content')
        </aside>
        @endif

        <!-- Layout page -->
        <div class="layout-page">
            
            {{-- Mobile Header --}}
            <nav class="mobile-header d-lg-none">
                <div class="d-flex align-items-center">
                    <button id="mobile-menu-btn" class="btn btn-icon btn-outline-secondary me-2">
                        <i class="bx bx-menu"></i>
                    </button>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/bassani.png') }}" alt="Bassani" style="height: 35px;">
                    </a>
                </div>
            </nav>

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                @if ($isFlex)
                <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                @else
                <div class="{{ $container }} flex-grow-1 container-p-y">
                @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')

                </div>
                <!-- / Content -->

                <!-- Footer -->
                @if ($isFooter)
                @include('layouts/sections/footer/footer')
                @endif
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
    <!-- / Layout container -->
</div>
<!-- / Layout wrapper -->

{{-- Mobile Menu Styles --}}
<style>
/* Mobile Header */
.mobile-header {
    position: sticky;
    top: 0;
    z-index: 1040;
    background: #fff;
    padding: 10px 15px;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

/* Mobile Overlay */
.mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1045;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    top: 0;
    left: -280px;
    bottom: 0;
    width: 280px;
    max-width: 80vw;
    background: #1F2A44;
    z-index: 1050;
    transition: left 0.3s ease;
    overflow-y: auto;
    overflow-x: hidden;
    display: flex !important;
    flex-direction: column !important;
}

.mobile-menu.active {
    left: 0;
}

.mobile-menu ul,
.mobile-menu .menu-inner {
    display: block !important;
    flex-direction: column !important;
}

.mobile-menu li,
.mobile-menu .menu-item {
    display: block !important;
    width: 100% !important;
    float: none !important;
}

.mobile-menu a,
.mobile-menu .menu-link {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    width: 100% !important;
    padding: 12px 16px !important;
    color: #D2D4DA !important;
    text-decoration: none !important;
    white-space: nowrap;
}

.mobile-menu a:hover,
.mobile-menu .menu-link:hover {
    background: rgba(222, 8, 2, 0.15) !important;
    color: #fff !important;
}

.mobile-menu .menu-item.active > .menu-link {
    background: rgba(222, 8, 2, 0.2) !important;
    color: #DE0802 !important;
}

.mobile-menu .menu-icon {
    margin-right: 12px !important;
    font-size: 1.2rem !important;
    width: 24px !important;
    text-align: center !important;
}

.mobile-menu .menu-title {
    flex: 1 !important;
    font-size: 0.9rem !important;
}

/* Submenu styles */
.mobile-menu .submenu {
    display: none;
    background: rgba(0, 0, 0, 0.2);
    padding: 0 !important;
    margin: 0 !important;
}

.mobile-menu .menu-item.open .submenu,
.mobile-menu .menu-item.active.open .submenu {
    display: block !important;
}

.mobile-menu .submenu .menu-item {
    padding-left: 24px !important;
}

.mobile-menu .submenu .menu-link {
    font-size: 0.85rem !important;
    padding: 10px 16px !important;
}

/* Mobile Menu Footer */
.mobile-menu .menu-footer {
    display: block !important;
    margin-top: auto !important;
    padding: 16px !important;
    background: rgba(0, 0, 0, 0.2) !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.mobile-menu .menu-footer .btn-outline-light {
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: #fff !important;
}

.mobile-menu .menu-footer .btn-outline-light:hover {
    background: rgba(222, 8, 2, 0.2) !important;
    border-color: #DE0802 !important;
}

.mobile-menu .menu-footer .avatar-initial {
    background: #DE0802 !important;
}

/* Hide desktop menu toggle on mobile */
.d-lg-block .layout-menu-toggle {
    display: none !important;
}

/* Add padding to content on mobile for header */
@media (max-width: 991.98px) {
    .layout-page {
        padding-top: 0;
    }
    
    .content-wrapper {
        min-height: calc(100vh - 56px);
    }
}

/* Show desktop sidebar on large screens */
@media (min-width: 992px) {
    .mobile-header,
    .mobile-menu,
    .mobile-overlay {
        display: none !important;
    }
    
    .layout-menu {
        display: flex !important;
        flex-direction: column !important;
        min-height: 100vh !important;
    }
    
    .layout-menu .menu-inner {
        flex: 1 !important;
        overflow-y: auto !important;
    }
    
    .layout-menu .menu-footer {
        display: block !important;
        margin-top: auto !important;
        flex-shrink: 0 !important;
    }
}

/* Body lock when menu open */
body.menu-open {
    overflow: hidden;
}
</style>

{{-- Mobile Menu Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-overlay');
    
    function openMenu() {
        if (mobileMenu) mobileMenu.classList.add('active');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        document.body.classList.add('menu-open');
    }
    
    function closeMenu() {
        if (mobileMenu) mobileMenu.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        document.body.classList.remove('menu-open');
    }
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }
    
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMenu);
    }
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
    
    // Close menu when clicking a menu link (for non-submenu items)
    if (mobileMenu) {
        mobileMenu.querySelectorAll('.menu-link:not(.menu-toggle)').forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
    }
    
    // Handle submenu toggling on mobile
    if (mobileMenu) {
        mobileMenu.querySelectorAll('.menu-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const parentItem = this.closest('.menu-item');
                if (parentItem) {
                    parentItem.classList.toggle('open');
                }
            });
        });
    }
    
    // Close button in mobile menu
    const mobileCloseBtn = document.getElementById('mobile-close-btn');
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeMenu();
        });
    }
});
</script>
@endsection
