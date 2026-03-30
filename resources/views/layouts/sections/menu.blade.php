@php
use Illuminate\Support\Facades\Route;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- Brand -->
    <div class="app-brand demo">
        <a href="{{url('/')}}" class="app-brand-link">
            <img src="{{ asset('assets/img/bassani.png') }}" alt="Bassani Móveis" style="height: 85px;" />
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="icon-base bx bx-chevron-left icon-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <div class="menu-inner pt-1 pb-1" id="menu-inner-wrapper">
    <ul class="menu-inner py-1">
        @foreach ($menuData->menu as $menu)

        {{-- adding active and open class if child is active %}}

        {{-- menu headers --}}
        @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
        @else

        {{-- active menu method --}}
        @php
        $activeClass = null;
        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
        }
        elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach($menu->slug as $slug){
        if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
        $activeClass = 'active open';
        }
        }
        }
        else{
        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
        $activeClass = 'active open';
        }
        }
        }
        @endphp

        {{-- main menu --}}
        <li class="menu-item {{$activeClass}}">
            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                @isset($menu->icon)
                <i class="menu-icon icon-base {{ $menu->icon }}"></i>
                @endisset
                <div class="menu-title">{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                @isset($menu->badge)
                <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
                @endisset
            </a>

            {{-- submenu --}}
            @isset($menu->submenu)
            @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
            @endisset
        </li>
        @endif
        @endforeach
    </ul>
    </div>

    <!-- User Info & Logout - Fixed at bottom -->
    <div class="menu-footer p-3 mt-auto">
        <!-- User Info -->
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light border-opacity-10">
            <div class="avatar-initial bg-danger me-2" style="width: 38px; height: 38px; font-weight: 600; color: white; font-size: 0.9rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <div class="fw-semibold text-white text-truncate" style="font-size: 0.875rem;">{{ auth()->user()->name ?? 'Usuário' }}</div>
                <small class="text-white-50 text-truncate d-block" style="font-size: 0.75rem;">{{ auth()->user()->email ?? '' }}</small>
            </div>
        </div>
        
        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                <i class="bx bx-power-off me-2"></i>
                <span>Sair</span>
            </button>
        </form>
    </div>

</aside>
