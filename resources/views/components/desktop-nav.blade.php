<nav class="main-nav" aria-label="Main navigation">
    <div class="nav-item"><a href="{{ route('beranda')}}"><i class="fas fa-home"></i> Beranda</a></div>

    <div class="nav-item">
    <a href="javascript:void(0)" class="has-sub"><i class="fas fa-info-circle"></i> Tentang</a>
    <div class="dropdown" role="menu" aria-hidden="true">
        <a href="{{ route('tentang.profil')}}"><i class="fas fa-user"></i> Profil</a>
        <a href="{{ route('tentang.visi-misi')}}"><i class="fas fa-bullseye"></i> Visi Misi</a>
    </div>
    </div>
    
    <div class="nav-item"><a href="{{ route('berita.berita')}}"><i class="fas fa-newspaper"></i> Berita</a></div>

    <div class="nav-item"><a href="{{ route('kunjungan.index')}}"><i class="fas fa-pen"></i> Buku Tamu</a></div>

    <div class="nav-item"><a href="{{ route('statistik')}}"><i class="fas fa-chart-bar"></i> Statistik</a></div>
</nav>