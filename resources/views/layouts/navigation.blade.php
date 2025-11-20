{{-- Sidebar --}}
<aside class="sidebar bg-white shadow vh-100 position-fixed top-0 start-0">
    {{-- Brand --}}
    <div class="p-4 border-bottom">
        <h2 class="text-xl fw-bold text-gray-700">{{ config('app.name') }}</h2>
    </div>

    {{-- Menu --}}
    <nav class="mt-4 px-3 d-flex flex-column gap-1">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center px-1 py-1  hover-bg {{ request()->routeIs('dashboard') ? 'bg-light fw-semibold' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        {{-- TEKNIK Section --}}
        @canany(['kerusakan_mesin_view', 'perbaikan_teknik_view'])
            <div class="-1 mt-3">
                <label class="bg-secondary text-white text-center py-1 w-100 d-block ">
                    TEKNIK
                </label>

                {{-- Sub-menu --}}
                <nav class="nav flex-column ms-2 mt-2">
                    @can('kerusakan_mesin_view')
                        <a href="{{ route('teknik.kerusakanmesin.index') }}"
                            class="d-flex align-items-center px-1 py-1  hover-bg {{ request()->routeIs('teknik.kerusakanmesin.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Kerusakan Mesin
                        </a>
                    @endcan

                    @can('perbaikan_teknik_view')
                        <a href="{{ route('teknik.perbaikanteknik.index') }}"
                            class="d-flex align-items-center px-1 py-1  hover-bg {{ request()->routeIs('teknik.perbaikanteknik.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-tools me-2"></i> Perbaikan Teknik
                        </a>
                    @endcan
                </nav>
            </div>
        @endcanany

        {{-- ADMINISTRATION Section --}}
        @canany(['user-view', 'view-roles', 'permissions_view'])
            <div class="-1 mt-3">
                <label class="bg-secondary text-white text-center py-1 w-100 d-block ">
                    ADMINISTRATION
                </label>

                {{-- Sub-menu --}}
                <nav class="nav flex-column ms-2 mt-2">
                    @can('user-view')
                        <a href="{{ route('users.index') }}"
                            class="d-flex align-items-center px-1 py-1  hover-bg {{ request()->routeIs('users.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-people-fill me-2"></i> Management Users
                        </a>
                    @endcan

                    @can('view roles')
                        <a href="{{ route('roles.index') }}"
                            class="d-flex align-items-center px-1 py-1  hover-bg {{ request()->routeIs('roles.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-shield-lock me-2"></i> Roles
                        </a>
                    @endcan

                    @can('mgpermissions_view')
                        <a href="{{ route('mgpermissions.index') }}"
                            class="d-flex align-items-center px-1 py-1 hover-bg {{ request()->routeIs('mgpermissions.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-key me-2"></i> Permissions
                        </a>
                    @endcan
                </nav>
            </div>
        @endcanany

    </nav>
</aside>
<style>
    /* Sidebar links */
    .sidebar a {
        text-decoration: none;
        /* hilangkan underline */
        color: #212529;
        /* teks hitam gelap, elegan */
        transition: background 0.2s, color 0.2s;
        /* smooth hover */
    }

    .sidebar a:hover {
        background-color: #f1f3f5;
        /* hover light */
        color: #000;
        /* teks saat hover */
    }

    .sidebar label {
        font-weight: 600;
        font-size: 0.85rem;
    }

    .hover-bg {
        border-radius: 0.35rem;
        /* jaga radius tetap konsisten */
    }

    /* Highlight menu aktif */
    .sidebar a.bg-light {
        font-weight: 600;
        background-color: #e9ecef !important;
    }
</style>
