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


        {{-- PRODUKSI Section --}}
        @canany(['target_harian_view', 'input_counter_view'])
            <div class="-1 mt-3">
                <label class="bg-secondary text-white text-center py-1 w-100 d-block">
                    PRODUKSI
                </label>
                <nav class="nav flex-column ms-2 mt-2">
                    @can('target_harian_view')
                        <a href="{{ route('produksi.targetharian.index') }}"
                            class="d-flex align-items-center px-1 py-1 hover-bg {{ request()->routeIs('produksi.targetharian.index.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Target Harian
                        </a>
                    @endcan
                    @can('input_counter_view')
                        <a href="{{ route('produksi.inputcounter.index') }}"
                            class="d-flex align-items-center px-1 py-1 hover-bg {{ request()->routeIs('produksi.inputcounter.index.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-calculator me-2"></i> Input Counter
                        </a>
                    @endcan
                    @can('input_counter_view')
                        <a href="{{ route('produksi.sisastokbahanbaku.index') }}"
                            class="d-flex align-items-center px-1 py-1 hover-bg {{ request()->routeIs('produksi.sisastokbahanbaku.index.*') ? 'bg-light fw-semibold' : '' }}">
                            <i class="bi bi-box-seam me-2"></i> Sisa Stok Bahan Baku
                        </a>
                    @endcan


                </nav>
            </div>
        @endcanany

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

        {{-- LAPORAN Section --}}
        @canany(['perbaikan_teknik_laporan'])
            <div class="-1 mt-3">
                <label class="bg-secondary text-white text-center py-1 w-100 d-block">
                    LAPORAN
                </label>

                {{-- Sub-menu Laporan --}}
                <nav class="nav flex-column ms-2 mt-2">

                    {{-- =====================
                 LAPORAN TEKNIK
            ====================== --}}
                    @can('perbaikan_teknik_laporan')
                        <div class="nav-item">

                            <a class="d-flex justify-content-between align-items-center px-1 py-1 hover-bg
                        {{ request()->is('laporan/teknik*') ? 'bg-light fw-semibold' : '' }}"
                                data-bs-toggle="collapse" href="#laporanTeknikMenu" role="button">

                                <span><i class="bi bi-hammer  me-2"></i> Laporan Teknik</span>
                                <i class="bi bi-caret-down-fill small"></i>
                            </a>

                            <div class="collapse ms-3 {{ request()->is('laporan/teknik*') ? 'show' : '' }}"
                                id="laporanTeknikMenu">
                                @can('perbaikan_teknik_laporan')
                                    <a href="{{ route('teknik.perbaikanteknik.laporan.bulanan') }}"
                                        class="d-block px-2 py-1 small hover-bg
                            {{ request()->routeIs('teknik.perbaikanteknik.laporan.bulanan') ? 'bg-light fw-bold' : '' }}">
                                        • Perbaikan Bulanan
                                    </a>

                                    <a href="{{ route('teknik.perbaikanteknik.laporan.tahunan') }}"
                                        class="d-block px-2 py-1 small hover-bg
                            {{ request()->routeIs('teknik.perbaikanteknik.laporan.tahunan') ? 'bg-light fw-bold' : '' }}">
                                        • Perbaikan Tahunan
                                    </a>
                                    <a href="{{ route('teknik.perbaikanteknik.laporan.daftarmasalahmesin') }}"
                                        class="d-block px-2 py-1 small hover-bg
                            {{ request()->routeIs('teknik.perbaikanteknik.laporan.daftarmasalahmesin') ? 'bg-light fw-bold' : '' }}">
                                        • Daftar Masalah Mesin
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan


                    {{-- =====================
                 LAPORAN PRODUKSI
            ====================== --}}
                    @can('laporan_produksi_view')
                        <div class="nav-item mt-1">

                            <a class="d-flex justify-content-between align-items-center px-1 py-1 hover-bg
                        {{ request()->is('laporan/produksi*') ? 'bg-light fw-semibold' : '' }}"
                                data-bs-toggle="collapse" href="#laporanProduksiMenu" role="button">

                                <span><i class="bi bi-kanban text-success me-2"></i> Laporan Produksi</span>
                                <i class="bi bi-caret-down-fill small"></i>
                            </a>

                            <div class="collapse ms-3 {{ request()->is('laporan/produksi*') ? 'show' : '' }}"
                                id="laporanProduksiMenu">

                                <a href="{{ route('produksi.laporan.bulanan') }}"
                                    class="d-block px-2 py-1 small hover-bg
                            {{ request()->routeIs('produksi.laporan.bulanan') ? 'bg-light fw-bold' : '' }}">
                                    • Laporan Bulanan
                                </a>

                                <a href="{{ route('produksi.laporan.tahunan') }}"
                                    class="d-block px-2 py-1 small hover-bg
                            {{ request()->routeIs('produksi.laporan.tahunan') ? 'bg-light fw-bold' : '' }}">
                                    • Laporan Tahunan
                                </a>

                            </div>
                        </div>
                    @endcan

                </nav>

            </div>
        @endcan


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
