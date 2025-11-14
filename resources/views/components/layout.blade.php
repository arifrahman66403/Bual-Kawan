<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>{{ $title }} | Buku Tamu Singgah</title> <link rel="icon" href="logo-bualkawan2.png" type="image/png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
/* ===== reset & base =====
   Komentar: styling dasar, jangan ubah kecuali disetujui
   Inisial: @ziedanet */
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

/* ===== header =====
   Komentar: header memakai motif pucuk rebung lokal
   Inisial: @ziedanet */
.header-full{background:rgba(0,0,0,0.86);position:fixed;left:0;right:0;top:0;z-index:1200;border-bottom:1px solid rgba(255,255,255,0.03);}

/* gunakan pucuk-rebung lokal (file berada di folder yang sama) @ziedanet */
.header-full::before{content:'';position:absolute;left:0;right:0;top:0;height:50px;background:url('pucuk-rebung.png') repeat-x;background-size:contain;opacity:0.28;transform:rotate(180deg);z-index:0}

.header-inner{max-width:var(--container);margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:12px 18px;height:var(--nav-height);position:relative;z-index:1}
.logo{display:flex;gap:12px;align-items:center}
.logo img{height:44px;width:44px;border-radius:8px;object-fit:cover}
.logo .brand{font-weight:800;color:#fff;font-size:1.03rem;letter-spacing:0.2px}

/* center menu area */
.center-area{display:flex;align-items:center;gap:28px}
nav.main-nav{display:flex;gap:18px;align-items:center}
nav.main-nav > .nav-item{position:relative}
nav a{color:#fff;text-decoration:none;font-weight:600;display:inline-flex;gap:8px;align-items:center;padding:8px 12px;border-radius:8px}
nav a:hover{color:var(--accent)}
/* HAPUS: nav a.active (Anda bisa mengaktifkannya lagi di HTML pada menu yang sesuai) */
nav a.active{color:var(--gold);box-shadow:0 8px 30px rgba(0,0,0,0.25)}

/* dropdown indicator */
.nav-item .has-sub::after{content:'\f078';font-family:'Font Awesome 6 Free';font-weight:900;margin-left:6px;font-size:0.7rem;transform:translateY(1px);opacity:0.9}

/* desktop dropdown */
.dropdown{position:absolute;left:0;top:100%;
background:#fff;border-radius:10px;padding:8px;box-shadow:0 8px 22px rgba(10,20,20,0.12);min-width:200px;display:none}
.dropdown a{display:block;color:#132a2a;padding:8px 12px;border-radius:8px;font-weight:700}
.nav-item:hover .dropdown{display:block; z-index: 100;}

/* ===== breadcrumb =====
   Komentar: margin-top diset agar menempel ke header, dan tinggi dinaikkan 1.5x
   Desktop: 140px -> 210px, Mobile: 120px -> 180px
   Inisial: @ziedanet */
/* margin-top set to nav height so breadcrumb touches header (tidak berjarak) */
.breadcrumb{margin-top:var(--nav-height);height:210px;background-image:url('jembatan-siak.jpg');
background-position:center;background-size:cover;display:flex;align-items:flex-end;justify-content:center;position:relative;overflow:hidden;border-bottom:4px solid var(--accent)}
.breadcrumb::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,0.28), rgba(0,0,0,0.45));z-index:0}
.breadcrumb .inner{position:relative;z-index:2;padding:18px;text-align:center;color:#fff;max-width:var(--container);margin:0 auto}
.breadcrumb h1{margin:0;font-size:1.3rem;font-weight:800;text-shadow:0 2px 8px rgba(0,0,0,0.6)}
.breadcrumb .crumbs{margin-top:8px;color:rgba(255,255,255,0.9);font-weight:700;display:flex;gap:8px;align-items:center;justify-content:center}
.breadcrumb .crumbs i{opacity:0.95}

/* ===== page container ===== */
.page{max-width:var(--container);margin:20px auto;padding:10px 18px 100px}

/* ===== info section (above footer) ===== */
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

/* ===== footer =====
   Komentar: footer tetap memanggil pucuk-rebung lokal untuk dekorasi
   Inisial: @ziedanet */
footer{background:#0b0b0b;color:#fff;padding:22px 18px 36px;text-align:center;margin-top:0;position:relative;overflow:hidden}
footer::after{content:'';position:absolute;left:0;right:0;bottom:0;height:56px;background:url('pucuk-rebung.png') repeat-x;background-size:contain;opacity:0.12}
footer p{margin:6px 0;color:rgba(255,255,255,0.9);font-weight:600}

/* ===== mobile bottom nav ===== */
.mobile-bottom{display:none}
.mobile-bottom .menu{
  position:fixed;left:0;right:0;bottom:0;
  height:var(--mobile-bottom-height);
  background:rgba(11,11,11,0.92);
  display:flex;align-items:center;
  /* KUNCI: Gunakan space-between dan padding di sini */
  justify-content:space-between; 
  padding:8px 12px;
  z-index:1400;
}
.mobile-bottom .menu button{
  background:transparent;border:none;color:#ddd;font-size:18px;
  display:flex;flex-direction:column;align-items:center;gap:4px;cursor:pointer;
  padding:6px 2px; /* Dikecilkan agar 5 item muat */
  border-radius:10px;
  flex: 1; /* KUNCI: 5 item akan berbagi ruang yang sama */
  min-width: 0;
  overflow: hidden;
}
.mobile-bottom .menu button small {
  font-size: 10px;
  white-space: nowrap; 
}
/* HAPUS: mobile button .active (Anda bisa mengaktifkannya lagi di HTML pada menu yang sesuai) */
.mobile-bottom .menu button.active{color:var(--gold)}

/* mobile submenu pop-up (slide up) */
.mobile-submenu{position:fixed;left:12px;right:12px;bottom:calc(var(--mobile-bottom-height) + 12px);background:#fff;border-radius:12px;padding:10px;box-shadow:0 20px 40px rgba(0,0,0,0.18);display:none;z-index:1500}
.mobile-submenu .item{display:flex;gap:12px;align-items:center;padding:8px;border-radius:8px;color:#132a2a;font-weight:700}

/* responsive */
@media(max-width:980px){
  /* HAPUS: .charts-grid (tidak diperlukan) */
  .header-inner{padding:12px}
  .center-area{gap:12px}
}
@media(max-width:720px){
  /* center and hide desktop nav - mobile bottom nav shows */
  nav.main-nav{display:none}
  .mobile-bottom{display:block}
  /* mobile breadcrumb height also 1.5x (120 -> 180) @ziedanet */
  .breadcrumb{height:180px}
  /* HAPUS: .stats-grid, .charts-grid (tidak diperlukan) */
  .header-inner{justify-content:space-between}
  .logo .brand{font-size:0.98rem}
}

/* @ziedanet: fix footer ketutup bottom nav di mobile */
@media (max-width: 768px) {
  body {
    padding-bottom: 72px; /* beri ruang setinggi menu mobile */
  }
}

/* center logo + menu content (visually) */
.header-spacer{flex:1}
.header-center-wrap{display:flex;align-items:center;gap:20px;justify-content:center;flex:0 1 auto}

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
</head>
<body>

  <x-header />

  <main class="page" role="main">
    {{ $slot}}
  </main>

  <x-bottom-nav />

  <x-section />

  <x-footer />

<script>
/* @ziedanet
   Script: Template Page (Hanya menyisakan interaksi menu mobile dan form kontak)
*/
(function(){
  
  /* ===== Mobile bottom nav interactions & submenu slide-up ===== */
  const mbTentang = document.getElementById('mb-tentang');
  const mobileSubmenu = document.getElementById('mobileSubmenu');

  function closeMobileSub(){ mobileSubmenu.style.display='none'; mobileSubmenu.setAttribute('aria-hidden','true'); }
  function openMobileSub(){ mobileSubmenu.style.display='block'; mobileSubmenu.setAttribute('aria-hidden','false'); mobileSubmenu.style.transform='translateY(0)'; mobileSubmenu.style.opacity='1'; }

  mbTentang.addEventListener('click', function(e){
    e.stopPropagation();
    // toggle submenu
    if (mobileSubmenu.style.display === 'block') closeMobileSub();
    else openMobileSub();
  });

  // click outside to close mobile submenu
  document.addEventListener('click', function(e){
    const isClickOnMenu = e.target.closest('.menu') || e.target.closest('#mobileSubmenu') || e.target.closest('#mb-tentang');
    if (!isClickOnMenu) closeMobileSub();
  });

  // mobile nav button navigation
  document.getElementById('mb-home').addEventListener('click', ()=> location.href = document.getElementById('mb-home').dataset.href );
  // PENAMBAHAN: Event listener untuk menu Berita mobile
  document.getElementById('mb-berita').addEventListener('click', ()=> location.href = document.getElementById('mb-berita').dataset.href );
  document.getElementById('mb-bukutamu').addEventListener('click', ()=> location.href = document.getElementById('mb-bukutamu').dataset.href );
  document.getElementById('mb-stat').addEventListener('click', ()=> location.href = document.getElementById('mb-stat').dataset.href );

  /* ===== contact form demo handler (keperluan tampilan saja) @ziedanet */
  window.onContactSend = function(e){
    e.preventDefault();
    const name = document.getElementById('contactName').value.trim();
    const email = document.getElementById('contactEmail').value.trim();
    const msg = document.getElementById('contactMessage').value.trim();
    if(!name||!email||!msg){ alert('Lengkapi form kontak.'); return; }
    alert('Terima kasih, pesan Anda sudah dikirim (demo).');
    document.getElementById('contactForm').reset();
  };

  /* Accessibility: hide mobile submenu when resizing to desktop @ziedanet */
  window.addEventListener('resize', function(){
    if(window.innerWidth > 720) closeMobileSub();
  });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>