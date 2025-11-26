<div class="mobile-bottom" aria-hidden="true">
    <div class="menu" role="navigation" aria-label="Mobile navigation">
      <button id="mb-home" data-href="{{ route('beranda')}}"><i class="fas fa-home"></i><small>Home</small></button>
      
      <button id="mb-tentang"><i class="fas fa-info-circle"></i><small>Tentang</small></button>
      
      <button id="mb-berita" data-href="{{ route('berita.berita')}}"><i class="fas fa-newspaper"></i><small>Berita</small></button>
      
      <button id="mb-bukutamu" data-href="{{ route('kunjungan.index')}}"><i class="fas fa-pen"></i><small>Buku Tamu</small></button>
      
      <button id="mb-stat" data-href="{{ route('statistik')}}"><i class="fas fa-chart-bar"></i><small>Statistik</small></button>

      @auth
      <button id="mb-logout" data-href="{{ route('logout')}}"><i class="fas fa-sign-out-alt"></i><small>Logout</small></button>
      @endauth
    </div>

    <div class="mobile-submenu" id="mobileSubmenu" role="menu" aria-hidden="true">
      <div class="item" onclick="location.href='{{ route('tentang.profil')}}'"><i class="fas fa-user"></i> Profil</div>
      <div class="item" onclick="location.href='{{ route('tentang.visi-misi')}}'"><i class="fas fa-bullseye"></i> Visi Misi</div>
    </div>
</div>