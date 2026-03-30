<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-link px-0" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Quick Access -->
            <li class="nav-item dropdown-shortcuts dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bx bx-grid-alt bx-sm"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0" data-bs-popper="none">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="text-body mb-0 me-auto">Acesso Rápido</h6>
                        </div>
                    </li>
                    <li class="dropdown-shortcuts-list scrollable-container">
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-primary rounded-circle mb-2">
                                    <i class="bx bx-dashboard fs-4"></i>
                                </span>
                                <a href="{{ route('dashboard.index') }}" class="stretched-link">Dashboard</a>
                                <small class="text-muted mb-0">Visão Geral</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-success rounded-circle mb-2">
                                    <i class="bx bx-cart fs-4"></i>
                                </span>
                                <a href="{{ route('sales.index') }}" class="stretched-link">Vendas</a>
                                <small class="text-muted mb-0">Gerenciar</small>
                            </div>
                        </div>
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-info rounded-circle mb-2">
                                    <i class="bx bx-package fs-4"></i>
                                </span>
                                <a href="{{ route('planned_shipments.index') }}" class="stretched-link">Entregas</a>
                                <small class="text-muted mb-0">Planejadas</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-warning rounded-circle mb-2">
                                    <i class="bx bx-user fs-4"></i>
                                </span>
                                <a href="{{ route('users.index') }}" class="stretched-link">Usuários</a>
                                <small class="text-muted mb-0">Equipe</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ Quick Access -->
            
            <!-- Notification -->
            <li class="nav-item dropdown-notifications dropdown me-2 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bx bx-bell bx-sm"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="text-body mb-0 me-auto">Notificações</h6>
                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Marcar todas como lidas">
                                <i class="bx fs-4 bx-envelope-open"></i>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item text-center py-4">
                                <i class="bx bx-bell fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Nenhuma notificação no momento</p>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top p-2">
                        <a href="#" class="btn btn-sm btn-outline-primary d-flex w-100 justify-content-center">
                            <span class="align-middle">Ver todas</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->
            
            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar-initial bg-danger" style="width: 40px; height: 40px; font-weight: 600; color: white; font-size: 1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial bg-danger me-3" style="width: 40px; height: 40px; font-weight: 600; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->name ?? auth()->user()->email ?? 'Usuário' }}</span>
                                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Meu Perfil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Configurações</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Sair</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User Dropdown -->
        </ul>
    </div>
</nav>
