<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="d-flex flex-column">
        <!-- Sidebar Header -->
        <div class="sidebar-header tooltip-custom" data-toggle="tooltip" data-placement="right" title="Home">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-nut-fill"></i>
                </div>
                <span class="sidebar-text sidebar-brand-text">Cash Book</span>
            </a>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav">
            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </div>

            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Businesses">
                <a href="{{ route('businesses.index') }}" class="sidebar-link {{ request()->routeIs('businesses.*') ? 'active' : '' }}">
                    <i class="bi bi-buildings-fill"></i>
                    <span class="sidebar-text">Businesses</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn tooltip-custom" data-toggle="tooltip" data-placement="right" title="Logout">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
