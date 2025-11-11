
<div class="bottom-nav d-lg-none">
    <x-nav-link 
        href="{{ route('admin.dashboard') }}" 
        class="bottom-nav-link"
        icon="bi-speedometer2" 
        label="Dashboard" 
        id="mobile-nav-dashboard"
        :active="request()->routeIs('admin.dashboard')" />

    <x-nav-link 
        href="{{ route('admin.pengajuan') }}" 
        class="bottom-nav-link"
        icon="bi-send-plus" 
        label="Pengajuan" 
        id="mobile-nav-pengajuan"
        :active="request()->routeIs('admin.pengajuan')" />

    <x-nav-link 
        href="{{ route('admin.riwayat') }}" 
        class="bottom-nav-link"
        icon="bi-clock-history" 
        label="Riwayat"
        id="mobile-nav-riwayat"
        :active="request()->routeIs('admin.riwayat')" />

    <x-nav-link 
        href="{{ route('login') }}" 
        class="bottom-nav-link"
        icon="bi-people" 
        label="Admin" 
        id="mobile-nav-admin"
        :active="request()->routeIs('admin.pengguna')" />
</div>