<div class="row">
    <div class="col-12 mb-4 desktop-nav-icons">
        <div class="d-flex flex-wrap justify-content-center">
            <x-nav-link 
                href="{{ route('login') }}" 
                icon="bi-speedometer2" 
                label="Dashboard" 
                id="dashboard"
                :active="request()->routeIs('admin.dashboard')" />

            <x-nav-link 
                href="{{ route('admin.verify') }}" 
                icon="bi-send-plus" 
                label="Pengajuan" 
                id="pengajuan"
                :active="request()->routeIs('admin.verify')" />

            <x-nav-link 
                href="{{ route('login') }}" 
                icon="bi-clock-history" 
                label="Riwayat Tamu" 
                id="riwayat"
                :active="request()->routeIs('admin.riwayat')" />

            <x-nav-link 
                href="{{ route('login') }}" 
                icon="bi-people" 
                label="Admin" 
                id="admin"
                :active="request()->routeIs('admin.pengguna')" />
        </div>
    </div>
</div>
