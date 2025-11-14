<x-layout title="Beranda">
    <style>
    /* @ziedanet - CSS Global dan Variabel */
    *{box-sizing:border-box}
    :root{
      --container:1100px;
      --bg:#f6f7f8;
      --muted:#9aa4a4;
      --card:#ffffff;
      --accent:#4CAF50;
      --accent-dark:#166443;
      --gold:#d4af37;
      --radius:12px;
      --shadow: 0 8px 28px rgba(15,20,20,0.06);
      --nav-height:78px;
      --mobile-bottom-height:72px;
    }
    body{margin:0;font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;background:var(--bg);color:#132a2a;-webkit-font-smoothing:antialiased;}
    
    /* KUNCI: Membuat guliran (scroll) menjadi halus */
    html {
        scroll-behavior: smooth; 
    }
    
    /* @ziedanet - Struktur Section */
    section { padding: 40px 18px; }
    h2 { text-align: center; margin-bottom: 20px; font-size: 1.6rem; font-weight: 800; color: #102121; }

    /* HEADER */
    .header-full{background:rgba(0,0,0,0.86);position:fixed;left:0;right:0;top:0;z-index:1200;border-bottom:1px solid rgba(255,255,255,0.03);}
    .header-full::before{content:'';position:absolute;left:0;right:0;top:0;height:50px;background:url('https://bualkawan.siakkab.go.id/pucuk-rebung.png') repeat-x;background-size:contain;opacity:0.28;transform:rotate(180deg);z-index:0}
    .header-inner{max-width:var(--container);margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:12px 18px;height:var(--nav-height);position:relative;z-index:1}
    .logo{display:flex;gap:12px;align-items:center}
    .logo img{height:44px;width:44px;border-radius:8px;object-fit:cover}
    .logo .brand{font-weight:800;color:#fff;font-size:1.03rem;letter-spacing:0.2px}
    .center-area{display:flex;align-items:center;gap:28px}
    nav.main-nav{display:flex;gap:18px;align-items:center}
    nav.main-nav > .nav-item{position:relative}
    nav a{color:#fff;text-decoration:none;font-weight:600;display:inline-flex;gap:8px;align-items:center;padding:8px 12px;border-radius:8px}
    nav a:hover{color:var(--accent)}
    nav a.active{color:var(--gold);box-shadow:0 8px 30px rgba(0,0,0,0.25)}
    .nav-item .has-sub::after{content:'\f078';font-family:'Font Awesome 6 Free';font-weight:900;margin-left:6px;font-size:0.7rem;transform:translateY(1px);opacity:0.9}
    .dropdown{position:absolute;left:0;top:100%;background:#fff;border-radius:10px;padding:8px;box-shadow:0 8px 22px rgba(10,20,20,0.12);min-width:200px;display:none}
    .dropdown a{display:block;color:#132a2a;padding:8px 12px;border-radius:8px;font-weight:700}
    .nav-item:hover .dropdown{display:block; z-index: 100;}

    /* SLIDER UTAMA (HERO) - POSISI BAWAH TENGAH */
    .slider { 
        position: relative; 
        margin-top: var(--nav-height); 
        height: 100vh; 
        overflow: hidden; 
        
        /* KUNCI LOAD AWAL: Sembunyikan dan beri posisi awal */
        opacity: 0; 
        transform: translateY(-10px); 
        transition: opacity 1.5s ease-out, transform 1.5s ease-out; 
    }
    
    /* KUNCI LOAD AWAL: Pindahkan ke posisi akhir saat loaded */
    .slider.loaded {
        opacity: 1;
        transform: translateY(0);
    }
    
    .slide { 
        position: absolute; 
        width: 100%; 
        height: 100%; 
        opacity: 0; 
        transition: opacity 1s ease-in-out; 
        z-index: 0;
        display: block;
        justify-content: unset; 
        align-items: unset;
    }
    
    .slide.active { opacity: 1; z-index: 1; } 
    .slide img { width: 100%; height: 100%; object-fit: cover; position: absolute; } 

    .slide::after {
        content: ''; 
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; 
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3); 
        display: block; 
        z-index: 1;
    }

    /* KUNCI ANIMASI SLIDE-INFO */
    .slide-info { 
        position: absolute; 
        
        bottom: 10%; 
        left: 0; 
        right: 0; 
        margin: 0 auto; 
        top: unset; 
        
        /* Kondisi awal (tersembunyi dan sedikit di bawah) */
        opacity: 0;
        transform: translateY(20px); 
        
        /* Transisi untuk fade-in/out saat slide berganti */
        transition: opacity 1.2s, transform 1.2s;
        
        max-width: 700px; 
        color: white; 
        text-align: center; 
        
        background-color: rgba(0,0,0,0.85); 
        padding: 30px 40px; 
        border-radius: var(--radius);
        
        z-index: 2; 
    }

    /* KUNCI: Animasi Fade-up saat slide AKTIF */
    .slide.active .slide-info {
        opacity: 1;
        transform: translateY(0); /* Kembali ke posisi normal */
        /* Beri sedikit delay agar animasi muncul setelah gambar slide penuh */
        transition: opacity 1.2s ease-out 0.2s, transform 1.2s ease-out 0.2s; 
    }

    .slide-info h2 { 
        margin: 0 0 10px 0; 
        font-size: 2.5rem; 
        color: #fff; 
        line-height: 1.1;
        text-shadow: none; 
    }
    .slide-info p {
        font-size: 1.1rem; 
        margin-bottom: 20px;
        font-weight: 400;
        text-shadow: none;
    }
    .slide-info a { 
        background-color: var(--gold); 
        color: #000; 
        padding: 10px 20px; 
        margin: 0 5px 0 0; 
        border-radius: 6px; 
        text-decoration: none; 
        font-weight: 700; 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }

    /* Tombol Navigasi Hero */
    .slider-btn { 
        position: absolute; 
        top: 50%; 
        transform: translateY(-50%); 
        background: rgba(0,0,0,0.5); 
        color: white; 
        border: none; 
        font-size: 1.8rem; 
        padding: 10px 15px; 
        cursor: pointer; 
        border-radius: 50%; 
        z-index: 10; 
        transition: background 0.2s; 
    }
    .slider-btn.prev { 
        left: 20px; 
    }
    .slider-btn.next { 
        right: 20px; 
    }

    /* KUNCI BARU: Tombol Floating Scroll Top/Bottom */
    .float-scroll-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        
        background-color: var(--gold);
        color: #132a2a;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        
        /* Animasi Transisi */
        opacity: 0;
        visibility: hidden;
        transform: scale(0.8);
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    /* Tampilkan tombol */
    .float-scroll-btn.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    /* SEKAPUR SIRIH */
    .sirih-container { max-width: var(--container); margin: 0 auto; padding: 20px; background-color: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); }
    .sirih-content { display: flex; gap: 20px; align-items: flex-start; text-align: justify; }
    .sirih-content img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 4px solid var(--gold); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .sirih-text p { margin: 0 0 15px 0; line-height: 1.7; }

    /* @ziedanet - BERITA SLIDER */
    .berita { 
        background: white; 
        position: relative; 
        padding: 40px 18px; 
    } 
    .berita-wrapper {
        max-width: var(--container);
        margin: 0 auto;
        overflow: hidden; 
        position: relative;
        padding: 0; 
    }
    .berita-container { 
        display: flex; 
        transition: transform 0.5s ease-in-out; 
        white-space: nowrap; 
    }
    .berita-card { 
        flex: 0 0 calc(33.333% - 13.333px); 
        margin-right: 20px; 
        background: var(--card); 
        border-radius: var(--radius); 
        overflow: hidden; 
        box-shadow: var(--shadow); 
        transition: transform 0.2s; 
        min-width: 300px; 
        display: inline-block; 
        white-space: normal;
    }
    .berita-card:last-child { margin-right: 0; }
    .berita-card:hover { transform: translateY(-5px); }
    .berita-card img { width: 100%; height: 180px; object-fit: cover; }
    .berita-body { padding: 15px; }
    .berita-body h3 { margin: 0 0 10px 0; font-size: 1.1rem; }
    .berita-body p { font-size: 0.9rem; color: var(--muted); margin-bottom: 15px; }
    .berita-body a { color: var(--gold); text-decoration: none; font-weight: 700; display: inline-block; }

    .berita-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        font-size: 1.2rem;
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 50%;
        z-index: 10;
        transition: background 0.2s;
    }
    .berita-prev { 
        left: 0; 
        margin-left: -40px; 
    } 
    .berita-next { 
        right: 0; 
        margin-right: -40px; 
    } 

    .berita-link-all { text-align: center; margin-top: 30px; }
    .berita-link-all a { background-color: var(--accent); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 700; }

    /* GALERI */
    .galeri { max-width: var(--container); margin: 0 auto; }
    .masonry { columns: 4 200px; column-gap: 1em; }
    .masonry img { width: 100%; border-radius: 8px; margin-bottom: 1em; }

    /* INFO SECTION DARI TEMPLATE */
    .info-section{background:linear-gradient(180deg,#2b2b2b,#232323);color:#fff;padding:18px 0 6px;margin-top:26px}
    .info-container{max-width:var(--container);margin:0 auto;display:flex;gap:24px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;padding:12px 18px}
    .info-col{flex:1;min-width:240px}
    .info-col h4{font-size:1.05rem;font-weight:800;margin:0 0 10px;padding-left:10px;border-left:4px solid var(--gold);color:#fff}
    .info-col ul{list-style:none;padding:0;margin:0}
    .info-col li{margin-bottom:8px}
    .info-col a{color:#fff;text-decoration:none;font-weight:700}
    .info-col a:hover{color:var(--gold)}
    #contactForm{display:flex;flex-direction:column;gap:8px}
    #contactForm input,#contactForm textarea{padding:10px;border-radius:8px;border:none;font-size:0.95rem}
    #contactForm button{background:var(--gold);border:none;padding:10px;border-radius:8px;font-weight:800;cursor:pointer;color:#222}

    /* FOOTER DARI TEMPLATE */
    footer{background:#0b0b0b;color:#fff;padding:22px 18px 36px;text-align:center;margin-top:0;position:relative;overflow:hidden}
    footer::after{content:'';position:absolute;left:0;right:0;bottom:0;height:56px;background:url('https://bualkawan.siakkab.go.id/pucuk-rebung.png') repeat-x;background-size:contain;opacity:0.12}
    footer p{margin:6px 0;color:rgba(255,255,255,0.9);font-weight:600}

    /* @ziedanet - MOBILE BOTTOM DARI TEMPLATE (AWAL) */
    .mobile-bottom{display:none}

    /* @ziedanet - MOBILE RESPONSIVE (Ditingkatkan) */
    @media(max-width:992px){
      .masonry{columns:2 150px;}
      .slide-info h2{font-size:1.8rem;}
      .slide-info p{font-size:1rem;}
      
      /* KUNCI MOBILE: Pastikan posisi di bawah tetap konsisten */
      .slide-info {
        max-width: 90%;
        bottom: 5%; /* Sedikit lebih ke bawah di mobile */
        padding: 20px;
        text-align: center;
        left: 0; 
        right: 0; 
        margin: 0 auto;
      }
      .slide-info a {
          padding: 8px 15px;
          font-size: 0.9rem;
      }
      
      /* Tombol Hero di Mobile */
      .slider-btn {
          font-size: 1.4rem;
          padding: 8px 12px;
      }
      .slider-btn.prev { 
          left: 10px; 
      }
      .slider-btn.next { 
          right: 10px; 
      }

      /* Tombol Berita di Mobile */
      .berita-wrapper { padding: 0 40px; } 
      .berita-nav-btn {
          top: 40%;
          padding: 8px 10px;
      }
      .berita-prev { left: 0; margin-left: 0; } 
      .berita-next { right: 0; margin-right: 0; } 


      /* Mobile Nav Show - PENTING: Menggunakan !important */
      nav.main-nav{display:none !important} 
      .mobile-bottom{display:block !important} 
      .header-inner{justify-content:space-between}
      .logo .brand{font-size:0.98rem}
      body {padding-bottom: 72px;} 

      /* KUNCI MOBILE: Tombol Floating Scroll */
      .float-scroll-btn {
          bottom: 80px; /* Di atas menu mobile bottom */
          right: 15px;
      }
    }

    /* @ziedanet - CSS Menu Bottom (Disesuaikan untuk 5 item) */
    .mobile-bottom .menu{
      position:fixed;
      left:0;right:0;bottom:0;
      height:var(--mobile-bottom-height);
      background:rgba(11,11,11,0.95);
      display:flex;
      align-items:center;
      justify-content:space-around;
      padding:8px 0; 
      z-index:1400;
    }
    .mobile-bottom .menu button{
      background:transparent;
      border:none;
      color:#ddd;
      font-size:18px;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:4px;
      cursor:pointer;
      padding:6px 2px;
      flex: 1; 
      min-width: 0;
      border-radius:10px
    }
    .mobile-bottom .menu button small {
        font-size: 0.6rem;
        font-weight: 600;
        white-space: nowrap; 
    }
    .mobile-bottom .menu button.active{color:var(--gold)}
    .mobile-submenu{
      position:fixed;
      left:12px;right:12px;
      bottom:calc(var(--mobile-bottom-height) + 12px);
      background:#fff;
      border-radius:12px;
      padding:10px;
      box-shadow:0 20px 40px rgba(0,0,0,0.18);
      display:none;
      z-index:1500
    }
    .mobile-submenu .item{display:flex;gap:12px;align-items:center;padding:8px;border-radius:8px;color:#132a2a;font-weight:700}
  </style>
<div class="slider">
    <div class="slide active">
      <img src="https://picsum.photos/id/1001/1600/900" alt="Slide 1">
      <div class="slide-info">
        <h2>Selamat Datang di Bual Kawan</h2>
        <p>Portal interaktif untuk menjalin komunikasi masyarakat dan pemerintah Kabupaten Siak.</p>
        <div>
            <a href="https://bualkawan.siakkab.go.id/buku-tamu.html"><i class="fas fa-pen"></i> Isi Buku Tamu</a>
            <a href="https://bualkawan.siakkab.go.id/statistik.html"><i class="fas fa-chart-bar"></i> Lihat Statistik</a>
        </div>
      </div>
    </div>
    <div class="slide">
      <img src="https://picsum.photos/id/1002/1600/900" alt="Slide 2">
      <div class="slide-info">
        <h2>Kemudahan Akses Informasi</h2>
        <p>Transparansi publik yang lebih dekat dengan warga Kabupaten Siak, tersedia 24 jam.</p>
        <div>
            <a href="https://bualkawan.siakkab.go.id/buku-tamu.html"><i class="fas fa-pen"></i> Isi Buku Tamu</a>
            <a href="https://bualkawan.siakkab.go.id/statistik.html"><i class="fas fa-chart-bar"></i> Lihat Statistik</a>
        </div>
      </div>
    </div>
    <div class="slide">
      <img src="https://picsum.photos/id/1003/1600/900" alt="Slide 3">
      <div class="slide-info">
        <h2>Siak Maju dan Bermarwah</h2>
        <p>Mari bersama-sama membangun Kabupaten Siak melalui partisipasi dan kritik yang konstruktif.</p>
        <div>
            <a href="https://bualkawan.siakkab.go.id/buku-tamu.html"><i class="fas fa-pen"></i> Isi Buku Tamu</a>
            <a href="https://bualkawan.siakkab.go.id/statistik.html"><i class="fas fa-chart-bar"></i> Lihat Statistik</a>
        </div>
      </div>
    </div>
    <button class="slider-btn prev"><i class="fa fa-chevron-left"></i></button>
    <button class="slider-btn next"><i class="fa fa-chevron-right"></i></button>
</div>

  <section class="sirih" id="sirih-section">
    <div class="sirih-container" data-aos="fade-right">
        <h2>Sekapur Sirih</h2>
        <hr style="border:0;border-top:1px solid rgba(0,0,0,0.1);margin:15px 0;">
        <div class="sirih-content">
            <img src="https://picsum.photos/200" alt="Pimpinan" data-aos="zoom-in" data-aos-delay="300">
            <div class="sirih-text" data-aos="fade-up">
                <p>Assalamualaikum warahmatullahi wabarakatuh.  
                Selamat datang di **Bual Kawan**, portal interaktif Kabupaten Siak yang dirancang untuk mempererat komunikasi antara pemerintah dan masyarakat.  
                Melalui layanan ini, warga dapat menyampaikan saran, masukan, maupun kritik secara langsung dan transparan. </p>
                <p>Portal ini juga menyediakan informasi terkait statistik partisipasi masyarakat, kegiatan pemerintah, serta berbagai layanan publik yang dapat diakses dengan mudah.  
                Kami berharap Bual Kawan menjadi sarana yang bermanfaat, efektif, dan nyaman untuk seluruh warga Kabupaten Siak.  
                Semoga melalui media ini, tercipta komunikasi yang harmonis antara pemerintah dan masyarakat, serta mendorong Siak menjadi kabupaten yang maju, bermarwah, dan dekat dengan warganya.  
                Terima kasih atas partisipasi dan perhatian Anda.</p>
            </div>
        </div>
    </div>
  </section>
  
  <section class="berita">
      <h2 data-aos="fade-up">Berita Terkini</h2>
      <div class="berita-wrapper">
          <div class="berita-container" id="beritaSlider">
              
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="100">
                  <img src="https://picsum.photos/id/101/400/250" alt="Berita 1">
                  <div class="berita-body">
                      <h3>Penerapan Program E-Government di Kabupaten Siak Berjalan Sukses</h3>
                      <p>15 Okt 2025 | Administrasi Publik</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="200">
                  <img src="https://picsum.photos/id/102/400/250" alt="Berita 2">
                  <div class="berita-body">
                      <h3>Bupati Ajak Masyarakat Partisipasi Aktif dalam Pembangunan Daerah</h3>
                      <p>12 Okt 2025 | Kegiatan Pemerintah</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="300">
                  <img src="https://picsum.photos/id/103/400/250" alt="Berita 3">
                  <div class="berita-body">
                      <h3>Diskominfo Siap Tingkatkan Kecepatan Akses Internet Desa</h3>
                      <p>08 Okt 2025 | Teknologi Informasi</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="400">
                  <img src="https://picsum.photos/id/104/400/250" alt="Berita 4">
                  <div class="berita-body">
                      <h3>Peluncuran Layanan Pengaduan Publik Terintegrasi Bual Kawan</h3>
                      <p>05 Okt 2025 | Layanan Publik</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="500">
                  <img src="https://picsum.photos/id/105/400/250" alt="Berita 5">
                  <div class="berita-body">
                      <h3>Sosialisasi Program Keluarga Harapan (PKH) di Wilayah Pesisir</h3>
                      <p>01 Okt 2025 | Kesejahteraan Sosial</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              <div class="berita-card" data-aos="zoom-in-up" data-aos-delay="600">
                  <img src="https://picsum.photos/id/106/400/250" alt="Berita 6">
                  <div class="berita-body">
                      <h3>Upaya Pelestarian Budaya Lokal Melalui Digitalisasi Arsip</h3>
                      <p>28 Sep 2025 | Kebudayaan</p>
                      <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                  </div>
              </div>
              
          </div>
          <button class="berita-nav-btn berita-prev" onclick="moveBerita( -1 )"><i class="fas fa-chevron-left"></i></button>
          <button class="berita-nav-btn berita-next" onclick="moveBerita( 1 )"><i class="fas fa-chevron-right"></i></button>
      </div>

      <div class="berita-link-all" data-aos="fade-up" data-aos-delay="700">
          <a href="https://bualkawan.siakkab.go.id/berita.html">Lihat Semua Berita <i class="fas fa-arrow-circle-right"></i></a>
      </div>
  </section>

  <section class="galeri">
    <h2 data-aos="fade-up">Galeri Kegiatan</h2>
    <div class="masonry">
      <img src="https://picsum.photos/id/200/300/400" data-aos="fade-up-right">
      <img src="https://picsum.photos/id/201/400/300" data-aos="fade-up-left">
      <img src="https://picsum.photos/id/202/300/300" data-aos="fade-up-right" data-aos-delay="100">
      <img src="https://picsum.photos/id/203/500/400" data-aos="fade-up-left" data-aos-delay="100">
    </div>
  </section>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Inisialisasi AOS
    AOS.init({
        duration: 1000, 
        once: true,     
    });

    // @ziedanet - JS SLIDER UTAMA (HERO)
    const slides = document.querySelectorAll('.slide');
    let index = 0;
    const nextButton = document.querySelector('.next');
    const prevButton = document.querySelector('.prev');
    const sliderElement = document.querySelector('.slider'); 
    
    function showSlide(i) { 
        slides.forEach((s, idx) => {
            s.classList.remove('active');
            s.style.zIndex = '0';
        });

        if (slides[i]) {
            slides[i].classList.add('active');
            slides[i].style.zIndex = '1';
        }
    }

    if (nextButton && prevButton) {
        nextButton.addEventListener('click', () => { 
            index = (index + 1) % slides.length; 
            showSlide(index); 
        });
        prevButton.addEventListener('click', () => { 
            index = (index - 1 + slides.length) % slides.length; 
            showSlide(index); 
        });
    }

    if (slides.length > 0) {
        // Otomatis pindah slide setiap 5 detik
        setInterval(() => { index = (index + 1) % slides.length; showSlide(index); }, 5000);
        
        // Panggil setelah semua aset dimuat
        window.addEventListener('load', () => {
            showSlide(0); 
            
            // KUNCI: Tambahkan class 'loaded' ke slider untuk memicu animasi fade-in
            if (sliderElement) {
                sliderElement.classList.add('loaded');
            }
        }); 
    }

    // @ziedanet - JS TOMBOL FLOAT TOP/BOTTOM (FINAL VERSION)
    const scrollToBtn = document.getElementById('scrollToBtn');
    
    function checkScrollPosition() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = document.documentElement.clientHeight;
        
        // Selalu tampilkan tombol
        scrollToBtn.classList.add('show');
        
        // Cek jika sudah menggulir ke bawah (lebih dari 200px dari atas)
        if (scrollTop > 200) {
            
            // Cek jika sudah mendekati bagian bawah (misal 100px dari footer)
            if ((scrollTop + clientHeight) >= (scrollHeight - 100)) {
                // Berada di bawah -> Tombol menjadi SCROLL KE ATAS
                scrollToBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
                scrollToBtn.setAttribute('title', 'Gulir ke Atas');
                scrollToBtn.dataset.direction = 'up';
            } else {
                // Berada di tengah -> Tombol menjadi SCROLL KE ATAS
                scrollToBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
                scrollToBtn.setAttribute('title', 'Gulir ke Atas');
                scrollToBtn.dataset.direction = 'up';
            }
        } else {
             // Berada di paling atas (Hero Section) -> Tombol SCROLL KE BAWAH
            scrollToBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
            scrollToBtn.setAttribute('title', 'Gulir ke Bawah');
            scrollToBtn.dataset.direction = 'down';
        }

        // Khusus Mobile: Sembunyikan tombol jika di paling atas agar tidak tumpang tindih 
        // dengan elemen lain pada saat load, namun tetap tampilkan jika ada scroll sedikit.
        if (window.innerWidth <= 992 && scrollTop < 100) {
             scrollToBtn.classList.remove('show');
        }
    }

    function handleScrollAction() {
        // Jika arah 'down', gulir ke section 'sirih-section' (konten pertama)
        if (scrollToBtn.dataset.direction === 'down') {
            document.getElementById('sirih-section')?.scrollIntoView({ behavior: 'smooth' });
        } else {
            // Jika arah 'up', gulir ke atas halaman
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Event Listeners untuk Tombol Float
    if(scrollToBtn) {
        scrollToBtn.addEventListener('click', handleScrollAction);
        window.addEventListener('scroll', checkScrollPosition);
        // Panggil saat load untuk menentukan posisi awal
        checkScrollPosition();
    }
    // AKHIR JS TOMBOL FLOAT


    // @ziedanet - JS SLIDER BERITA
    const beritaSlider = document.getElementById('beritaSlider');
    const beritaCards = document.querySelectorAll('#beritaSlider .berita-card');
    let currentBeritaIndex = 0;
    
    function getCardsPerView() {
        if (window.innerWidth <= 768) return 1;
        if (window.innerWidth <= 1000) return 2; 
        return 3;
    }

    function moveBerita(direction) {
        if (beritaCards.length === 0) return;

        const totalCards = beritaCards.length;
        const cardsPerView = getCardsPerView();
        
        let maxIndex = totalCards - cardsPerView;
        if (maxIndex < 0) maxIndex = 0;

        currentBeritaIndex += direction;
        
        if (currentBeritaIndex < 0) {
            currentBeritaIndex = 0; 
        } else if (currentBeritaIndex > maxIndex) {
            currentBeritaIndex = maxIndex; 
        }

        // KUNCI PERBAIKAN: Hitung lebar kartu setiap kali geser untuk akurasi.
        const cardWidth = beritaCards[0].offsetWidth + 20; 
        let offset = currentBeritaIndex * cardWidth;

        if (beritaSlider) {
            beritaSlider.style.transform = `translateX(-${offset}px)`;
        }
    }

    // Set posisi awal dan tambahkan listener untuk resize (untuk responsif)
    window.addEventListener('resize', () => {
        currentBeritaIndex = 0;
        moveBerita(0);
        checkScrollPosition(); // Juga cek posisi tombol float saat resize
    });

    // PENTING: Inisialisasi Berita Slider setelah halaman selesai dimuat
    window.addEventListener('load', () => {
        moveBerita(0);
    });
    
    // @ziedanet - JS INTERAKSI KONTAK
    window.onContactSend = function(e){
        e.preventDefault();
        const name = document.getElementById('contactName')?.value.trim();
        const email = document.getElementById('contactEmail')?.value.trim();
        const msg = document.getElementById('contactMessage')?.value.trim();
        if(!name||!email||!msg){ alert('Mohon lengkapi semua form kontak.'); return; }
        alert('Terima kasih, pesan Anda sudah dikirim (demo).');
        document.getElementById('contactForm').reset();
    };


    // @ziedanet - JS MOBILE NAVIGASI
    const mbTentang = document.getElementById('mb-tentang');
    const mobileSubmenu = document.getElementById('mobileSubmenu');

    function closeMobileSub(){ 
      if(mobileSubmenu) {
        mobileSubmenu.style.display='none'; 
        mobileSubmenu.setAttribute('aria-hidden','true');
      }
    }
    function openMobileSub(){ 
      if(mobileSubmenu) {
        mobileSubmenu.style.display='block'; 
        mobileSubmenu.setAttribute('aria-hidden','false'); 
      }
    }

    if (mbTentang) {
        mbTentang.addEventListener('click', function(e){
            e.stopPropagation();
            if (mobileSubmenu && mobileSubmenu.style.display === 'block') closeMobileSub();
            else openMobileSub();
        });
    }

    // @ziedanet - Tutup submenu jika klik di luar
    document.addEventListener('click', function(e){
        const isClickOnMenu = e.target.closest('.menu') || e.target.closest('#mobileSubmenu') || e.target.closest('#mb-tentang');
        if (!isClickOnMenu) closeMobileSub();
    });

    // @ziedanet - Event listener untuk navigasi mobile yang langsung menuju link
    document.getElementById('mb-home')?.addEventListener('click', ()=> location.href = document.getElementById('mb-home').dataset.href );
    document.getElementById('mb-berita')?.addEventListener('click', ()=> location.href = document.getElementById('mb-berita').dataset.href );
    document.getElementById('mb-bukutamu')?.addEventListener('click', ()=> location.href = document.getElementById('mb-bukutamu').dataset.href );
    document.getElementById('mb-stat')?.addEventListener('click', ()=> location.href = document.getElementById('mb-stat').dataset.href );

    // @ziedanet - Tutup submenu saat resize ke desktop
    window.addEventListener('resize', function(){
        if(window.innerWidth > 992) closeMobileSub();
    });
  </script>
</x-layout>  