<div class="row">
    <div class="col-12 mb-4 desktop-nav-icons">
        <div class="d-flex flex-wrap justify-content-center">
            <x-nav-link 
                href="{{ route('login') }}" 
                class="nav-link-icon"
                icon="bi-speedometer2" 
                label="Dashboard" 
                id="nav-dashboard"
                :active="request()->routeIs('admin.dashboard')" />

            <x-nav-link 
                href="{{ route('admin.pengajuan') }}" 
                class="nav-link-icon"
                icon="bi-send-plus" 
                label="Pengajuan" 
                id="nav-pengajuan"
                :active="request()->routeIs('admin.pengajuan')" />

            <x-nav-link 
                href="{{ route('admin.riwayat') }}" 
                class="nav-link-icon"
                icon="bi-clock-history" 
                label="Riwayat Tamu" 
                id="nav-riwayat"
                :active="request()->routeIs('admin.riwayat')" />

            <x-nav-link 
                href="{{ route('login') }}" 
                class="nav-link-icon"
                icon="bi-people" 
                label="Admin" 
                id="nav-admin"
                :active="request()->routeIs('admin.pengguna')" />
        </div>
    </div>
</div>
