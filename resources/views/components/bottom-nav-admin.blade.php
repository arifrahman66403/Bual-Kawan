<div class="bottom-nav d-lg-none">
    <x-nav-link-admin 
        href="{{ route('admin.dashboard') }}" 
        class="bottom-nav-link"
        icon="bi-speedometer2" 
        label="Dashboard" 
        id="mobile-nav-dashboard"
        :active="request()->routeIs('admin.dashboard')" />

    <x-nav-link-admin 
        href="{{ route('admin.pengajuan') }}" 
        class="bottom-nav-link"
        icon="bi-send-plus" 
        label="Pengajuan" 
        id="mobile-nav-pengajuan"
        :active="request()->routeIs('admin.pengajuan')" />

    <x-nav-link-admin 
        href="{{ route('admin.riwayat') }}" 
        class="bottom-nav-link"
        icon="bi-clock-history" 
        label="Riwayat"
        id="mobile-nav-riwayat"
        :active="request()->routeIs('admin.riwayat')" />

    <x-nav-link-admin 
        href="{{ route('admin.gallery.index') }}" 
        class="bottom-nav-link"
        icon="bi-images" 
        label="Gallery" 
        id="mobile-nav-gallery"
        :active="request()->routeIs('admin.gallery.index')" />

    <x-nav-link-admin 
        href="{{ route('admin.slider.index') }}"
        class="bottom-nav-link"
        icon="bi-film" 
        label="Slider Foto" 
        id="mobile-nav-slider"
        :active="request()->routeIs('admin.slider.index')" />

    <x-nav-link-admin 
        href="{{ route('admin.users.index') }}"
        class="bottom-nav-link"
        icon="bi-people" 
        label="Admin" 
        id="mobile-nav-admin"
        :active="request()->routeIs('admin.users.index')" />
</div>