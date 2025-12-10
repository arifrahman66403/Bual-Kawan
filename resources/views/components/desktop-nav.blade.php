<nav class="main-nav" aria-label="Main navigation">
    <div class="nav-item">
        <a href="{{ route('beranda')}}" class="{{ Request::is('/') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Beranda
        </a>
    </div>

    <div class="nav-item">
        <a href="javascript:void(0)" class="has-sub {{ Request::is('tentang*') ? 'active' : '' }}">
            <i class="fas fa-info-circle"></i> Tentang
        </a>
        <div class="dropdown" role="menu" aria-hidden="true">
            <a href="{{ route('tentang.profil')}}" class="{{ Request::is('tentang/profil') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profil
            </a>
            <a href="{{ route('tentang.visi-misi')}}" class="{{ Request::is('tentang/visi-misi') ? 'active' : '' }}">
                <i class="fas fa-bullseye"></i> Visi Misi
            </a>
        </div>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('berita.berita')}}" class="{{ Request::is('berita*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Berita
        </a>
    </div>

    <div class="nav-item">
        <a href="{{ route('kunjungan.index')}}" class="{{ Request::is('kunjungan*') ? 'active' : '' }}">
            <i class="fas fa-pen"></i> Buku Tamu
        </a>
    </div>

    <div class="nav-item">
        <a href="{{ route('statistik')}}" class="{{ Request::is('statistik*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Statistik
        </a>
    </div>

    @auth
    <div class="nav-item">
        <a href="{{ route('logout')}}">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
    @endauth
</nav>