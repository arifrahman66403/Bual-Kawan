<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>{{ $title }} | E-Singgah</title>

<link rel="icon" type="image/png" href="https://bualkawan.siakkab.go.id/logo-bualkawan2.png">

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
   Komentar: TINGGI DITINGKATKAN (300px)
   Inisial: @ziedanet */
/* margin-top set to nav height so breadcrumb touches header (tidak berjarak) */
.breadcrumb{margin-top:var(--nav-height);
/* PERUBAHAN TINGGI DI SINI */
height:210px; 
/* GANTI URL GAMBAR DUMMY BREADCRUMB */
background-image:url('jembatan.siak.jpg');
background-position:center;background-size:cover;display:flex;align-items:flex-end;justify-content:center;position:relative;overflow:hidden;border-bottom:4px solid var(--accent)}
.breadcrumb::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,0.28), rgba(0,0,0,0.45));z-index:0}
.breadcrumb .inner{position:relative;z-index:2;padding:18px;text-align:center;color:#fff;max-width:var(--container);margin:0 auto}
.breadcrumb h1{margin:0;font-size:1.3rem;font-weight:800;text-shadow:0 2px 8px rgba(0,0,0,0.6)}
.breadcrumb .crumbs{margin-top:8px;color:rgba(255,255,255,0.9);font-weight:700;display:flex;gap:8px;align-items:center;justify-content:center}
.breadcrumb .crumbs i{opacity:0.95}

/* ===== page container ===== */
.page{max-width:var(--container);margin:20px auto;padding:10px 18px 100px}

/* #1: Custom Styling untuk Halaman Berita */
.article-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
    font-weight: 900;
}
.article-meta {
    font-size: 0.95rem;
    color: var(--muted);
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
}
.article-content {
    line-height: 1.8;
    font-size: 1.05rem;
    color: #333;
}
.article-content p {
    margin-bottom: 20px;
    text-align: justify;
}
.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius);
    margin: 15px 0;
    box-shadow: var(--shadow);
}
.article-content blockquote {
    border-left: 5px solid var(--accent);
    padding: 10px 20px;
    margin: 20px 0;
    background: #eaf7ea;
    font-style: italic;
    color: #166443;
}

/* #7: Tag Section Styling */
.tag-section {
    margin-top: 25px;
    padding-bottom: 15px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.tag-section strong {
    font-weight: 700;
    color: #132a2a;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 1.05rem;
}
.tag-link {
    display: inline-block;
    padding: 6px 12px;
    background: var(--card);
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 0.9rem;
    color: var(--accent-dark);
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s;
}
.tag-link:hover {
    background: var(--accent);
    color: white;
}


/* #2: Social Share */
.share-section {
    margin-top: 30px;
    border-top: 1px solid #eee;
    padding-top: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}
.share-section strong {
    font-weight: 700;
}
.share-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-size: 18px;
    transition: transform 0.2s;
}
.share-button:hover {
    transform: scale(1.1);
}
.share-whatsapp { background: #25D366; }
.share-facebook { background: #1877F2; }
.share-twitter { background: #1DA1F2; }
.share-instagram { 
    background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
}
.share-tiktok { 
    background: #010101; 
}


/* #3: Berita Terkini */
.related-news {
    margin-top: 50px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.related-news h3 {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 25px;
    padding-left: 10px;
    border-left: 4px solid var(--accent);
}
.news-list {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.news-item {
    flex: 1 1 30%; /* Tiga kolom di desktop */
    min-width: 280px;
    background: var(--card);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: transform 0.3s;
    text-decoration: none;
    color: inherit;
}
.news-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(15,20,20,0.1);
}
.news-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.news-item-body {
    padding: 15px;
}
.news-item h4 {
    font-size: 1.05rem;
    font-weight: 800;
    margin: 0 0 8px;
    line-height: 1.4;
}
.news-item-meta {
    font-size: 0.85rem;
    color: var(--muted);
}

/* #4: Kolom Komentar */
.comment-section {
    margin-top: 50px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.comment-section h3 {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 25px;
    padding-left: 10px;
    border-left: 4px solid var(--accent);
}
.comment-form textarea {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-family: inherit;
    font-size: 1rem;
    margin-bottom: 10px;
}
.comment-form button {
    background: var(--accent);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
}
.comment-form button:hover {
    background: var(--accent-dark);
}
.comment-list {
    margin-top: 25px;
    list-style: none;
    padding: 0;
}
.comment-item {
    background: var(--card);
    padding: 15px;
    border-radius: var(--radius);
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.comment-header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    gap: 10px;
}
.comment-author {
    font-weight: 800;
    color: var(--accent-dark);
}
.comment-date {
    font-size: 0.85rem;
    color: var(--muted);
}
.comment-body {
    line-height: 1.6;
}

/* #1: Custom Styling untuk Halaman Profil */
.profile-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
    font-weight: 900;
    color: var(--accent-dark);
}
.profile-content {
    line-height: 1.8;
    font-size: 1.05rem;
    color: #333;
    background: var(--card);
    padding: 30px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}
.profile-content h2 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-top: 30px;
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 4px solid var(--gold);
}
.profile-content p {
    margin-bottom: 20px;
    text-align: justify;
}
.profile-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius);
    margin: 15px 0;
    box-shadow: var(--shadow);
}
.profile-content ul, .profile-content ol {
    margin: 15px 0;
    padding-left: 25px;
}
.profile-content li {
    margin-bottom: 8px;
}

/* Tabel Styling for Structure/History */
.profile-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
.profile-content th, .profile-content td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
.profile-content th {
    background-color: #f2f2f2;
    font-weight: 700;
    color: var(--accent-dark);
}


/* #5: Animasi Fade-In */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}
.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

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

    /* ===== stats cards ===== */
    .stats-grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin-bottom:18px}
    .stat-card{border-radius:12px;color:#fff;padding:18px;display:flex;gap:12px;align-items:center;box-shadow:0 8px 20px rgba(0,0,0,0.08);position:relative;overflow:hidden;min-height:96px;transition:transform .18s, box-shadow .18s}
    .stat-card:hover{transform:translateY(-6px);box-shadow:0 14px 34px rgba(0,0,0,0.14)}
    .stat-icon{width:64px;height:64px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:28px;background:rgba(255,255,255,0.12)}
    .stat-body{flex:1}
    .stat-body h3{margin:0;font-size:1.45rem;font-weight:800;line-height:1}
    .stat-body p{margin:6px 0 0;font-weight:700;opacity:0.95}

    /* color variants (dashboard modern) */
    .c-green{background:linear-gradient(135deg,#2ecc71,#27ae60)}
    .c-blue{background:linear-gradient(135deg,#3498db,#2b6fa3)}
    .c-purple{background:linear-gradient(135deg,#9b59b6,#6f42c1)}
    .c-orange{background:linear-gradient(135deg,#f39c12,#d87e00)}
    .c-pink{background:linear-gradient(135deg,#ff6b9f,#e84a9b)}

    /* ===== charts area ===== */
    .charts-grid{display:grid;gap:18px;grid-template-columns:repeat(3,1fr);margin-top:8px}
    .chart-card{background:var(--card);padding:14px;border-radius:12px;box-shadow:var(--shadow);min-height:240px;display:flex;flex-direction:column}
    .chart-card h4{margin:0 0 8px;font-size:1rem}
    .chart-card .canvas-wrap{flex:1;min-height:180px;position:relative}

    /* responsive */
    @media(max-width:980px){
    .charts-grid{grid-template-columns:repeat(2,1fr)}
    .header-inner{padding:12px}
    .center-area{gap:12px}
    }

    .berita-link-all { text-align: center; margin-top: 30px; }
    .berita-link-all a { background-color: var(--accent); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 700; }

    /* ===== Berita List and Search Styles ===== */
    .search-bar {display: flex; gap: 10px; margin-bottom: 25px; padding: 15px; background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow);}
    .search-bar input {flex-grow: 1; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;}
    .search-bar button {background: var(--accent); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s;}
    .search-bar button:hover {background: var(--accent-dark);}

    .news-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;}
    .news-item {
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: transform 0.2s;
        text-decoration: none; /* Penting: news-item adalah <a> */
        color: inherit;
        display: block; /* Agar news-item mengisi grid */
    }
    .news-item:hover {
        transform: translateY(-5px);
    }
    .news-item:hover h3 {
        color: var(--accent-dark); /* Efek hover pada judul */
    }

    .news-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }
    .news-content {
        padding: 15px;
    }
    .news-content h3 {
        margin-top: 0;
        font-size: 1.15rem;
        line-height: 1.4;
        font-weight: 800;
        height: 3.45rem; 
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        color: #132a2a; /* Pastikan warna judul benar */
        transition: color 0.2s;
    }
    .news-meta {
        font-size: 0.85rem;
        color: var(--muted);
        margin-bottom: 10px;
    }
    .news-meta span {
        margin-right: 15px;
    }
    .news-excerpt {
        font-size: 0.95rem;
        color: #444;
        line-height: 1.5;
        height: 4.5em; 
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    /* Pagination Styles */
    .pagination {display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px;}
    .pagination button {background: var(--card); color: #132a2a; border: 1px solid #ddd; padding: 10px 15px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s, color 0.2s; min-width: 40px;}
    .pagination button:hover:not(.active), .pagination button:disabled {background: var(--bg);}
    .pagination button.active {background: var(--accent); color: #fff; border-color: var(--accent); cursor: default;}

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

/* =======================================================
     Tambahan Skrip untuk Fitur Baru
     ======================================================= */

  // #5: Animasi Fade-In saat Scroll (Intersection Observer)
  const fadeInElements = document.querySelectorAll('.fade-in');
  const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
  };

  const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
          if (entry.isIntersecting) {
              entry.target.classList.add('visible');
              observer.unobserve(entry.target); // Stop observing once visible
          }
      });
  }, observerOptions);

  fadeInElements.forEach(el => {
      observer.observe(el);
  });

  /* @ziedanet
   Script: Statistik page (Chart.js + mobile menu interactions)
   Ringkas & hanya untuk fitur yang dipakai
*/
(function(){
  // helper: format date
  function formatDate(d){ return d.toLocaleString('id-ID', { day:'2-digit', month:'short', year:'numeric' }); }
  document.getElementById('lastUpdated').textContent = formatDate(new Date());

  /* ===== dummy data for charts (Chart.js) =====
     Data dummy ini tidak diubah (sesuai permintaan) @ziedanet */
  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const monthlyData = [120,180,150,210,250,290,310,330,300,280,260,300];
  const origins = ['Siak','Pekanbaru','Riau','Bandung','Jakarta'];
  const originData = [420,180,120,90,60];
  const ratingLabels = ['5 ★','4 ★','3 ★','2 ★','1 ★'];
  const ratingData = [420,150,50,20,10];

  /* ===== Chart: Monthly (bar) - fix: animation not looping and stable sizing
     Jangan ubah bagian ini karena sudah sesuai (perbaikan sebelumnya) @ziedanet */
  const ctxM = document.getElementById('chartMonthly').getContext('2d');
  const chartMonthly = new Chart(ctxM, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Pengunjung',
        data: monthlyData.slice(), // use copy
        backgroundColor: months.map((m,i)=>`rgba(${50 + i*8},${120 + (i*6)},${200 - i*5},0.9)`),
        borderRadius:6,
        barThickness:18
      }]
    },
    options: {
      plugins:{legend:{display:false}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{
        duration:600,
        easing:'easeOutCubic',
        // don't animate on resize repeatedly
        animateRotate:false,
        animateScale:false
      },
      scales:{
        x:{grid:{display:false}, ticks:{maxRotation:0,minRotation:0}},
        y:{beginAtZero:true}
      },
      interaction:{mode:'index', intersect:false}
    }
  });

  /* ===== Chart: Origin (doughnut) ===== */
  const ctxO = document.getElementById('chartOrigin').getContext('2d');
  const chartOrigin = new Chart(ctxO, {
    type: 'doughnut',
    data: {
      labels: origins,
      datasets: [{
        data: originData.slice(),
        backgroundColor: ['#2ecc71','#3498db','#9b59b6','#f39c12','#ff6b9f'],
        hoverOffset:8
      }]
    },
    options:{
      plugins:{legend:{position:'bottom'}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{duration:600,easing:'easeOutCubic'}
    }
  });

  /* ===== Chart: Feedback (pie) ===== */
  const ctxF = document.getElementById('chartFeedback').getContext('2d');
  const chartFeedback = new Chart(ctxF, {
    type: 'pie',
    data: {
      labels: ratingLabels,
      datasets: [{
        data: ratingData.slice(),
        backgroundColor: ['#27ae60','#2f80ed','#f2c94c','#f2994a','#eb5757'],
      }]
    },
    options:{
      plugins:{legend:{position:'bottom'}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{duration:600,easing:'easeOutCubic'}
    }
  });




  /* ==========================================================
     News List, Search, and Pagination Logic (Tidak Berubah)
     ========================================================== */
    const newsContainer = document.getElementById('news-container');
    const paginationControls = document.getElementById('pagination-controls');
    const noResults = document.getElementById('no-results');
    const itemsPerPage = 12;
    let currentPage = 1;
    let filteredNews = [];

    const baseImageUrl = (id) => `https://picsum.photos/seed/${id}/400/180`;
    const newsDetailUrl = "berita-detail.html"; 

    // --- Dummy Data (30 Berita) ---
    const allNewsData = [
        { title: "Pembukaan Festival Seni dan Budaya Siak 2025", date: "24 Okt 2025", category: "Budaya", excerpt: "Festival Seni dan Budaya tahunan Siak dibuka dengan meriah di tepi Sungai Siak, menampilkan tarian tradisional dan kuliner lokal.", image: baseImageUrl(100) },
        { title: "Bupati Ajak Masyarakat Jaga Kebersihan Lingkungan", date: "23 Okt 2025", category: "Pemerintahan", excerpt: "Dalam pidato mingguan, Bupati Siak menekankan pentingnya peran aktif masyarakat dalam menjaga kebersihan sungai dan fasilitas publik.", image: baseImageUrl(101) },
        { title: "Siak Raih Penghargaan Kota Sehat Nasional", date: "22 Okt 2025", category: "Kesehatan", excerpt: "Berkat kerjasama lintas sektor, Kabupaten Siak kembali meraih predikat Kota Sehat tingkat nasional untuk kelima kalinya secara berturut-turut.", image: baseImageUrl(102) },
        { title: "Pembangunan Infrastruktur Jalan Tol Siak-Pekanbaru Capai 80%", date: "21 Okt 2025", category: "Pembangunan", excerpt: "Proyek strategis nasional, Jalan Tol yang menghubungkan Siak dan Pekanbaru, memasuki tahap akhir penyelesaian.", image: baseImageUrl(103) },
        { title: "Pelatihan Digital Marketing untuk UMKM Siak", date: "20 Okt 2025", category: "Ekonomi", excerpt: "Diskominfo Siak menggelar pelatihan intensif bagi pelaku Usaha Mikro Kecil dan Menengah (UMKM) untuk meningkatkan penjualan online mereka.", image: baseImageUrl(104) },
        { title: "Persiapan Siak Menjadi Tuan Rumah PON 2028", date: "19 Okt 2025", category: "Olahraga", excerpt: "Berbagai venue olahraga di Siak mulai direnovasi dan dibangun baru untuk menyambut perhelatan akbar Pekan Olahraga Nasional (PON) 2028.", image: baseImageUrl(105) },
        { title: "Sosialisasi Penggunaan Aplikasi Pelayanan Publik Terbaru", date: "18 Okt 2025", category: "Pemerintahan", excerpt: "Pemerintah Kabupaten Siak meluncurkan aplikasi mobile baru untuk mempermudah akses layanan publik seperti perizinan dan kependudukan.", image: baseImageUrl(106) },
        { title: "Penemuan Situs Bersejarah Baru di Kawasan Istana Siak", date: "17 Okt 2025", category: "Sejarah", excerpt: "Tim arkeolog menemukan artefak dan struktur kuno yang diyakini merupakan bagian dari kompleks Istana Siak di masa lampau.", image: baseImageUrl(107) },
        { title: "Panen Raya Padi Unggul di Kecamatan Tualang", date: "16 Okt 2025", category: "Pertanian", excerpt: "Petani di Tualang berhasil mencatat rekor panen padi dengan varietas unggul baru, menandakan ketahanan pangan lokal yang kuat.", image: baseImageUrl(108) },
        { title: "Promosi Wisata Alam Siak: Hutan Mangrove Mempesona", date: "15 Okt 2025", category: "Pariwisata", excerpt: "Dinas Pariwisata gencar mempromosikan keindahan Hutan Mangrove Siak sebagai destinasi ekowisata unggulan di Riau.", image: baseImageUrl(109) },
        { title: "Pemerintah Siak Gelar Operasi Pasar Murah Jelang Akhir Tahun", date: "14 Okt 2025", category: "Ekonomi", excerpt: "Untuk menstabilkan harga, operasi pasar murah digelar di 14 kecamatan, menjual kebutuhan pokok dengan harga terjangkau.", image: baseImageUrl(110) },
        { title: "Waspada Musim Hujan: Pencegahan Bencana Banjir", date: "13 Okt 2025", category: "Bencana", excerpt: "BPBD Siak mengimbau masyarakat untuk waspada terhadap potensi banjir dan longsor seiring dengan intensitas hujan yang meningkat.", image: baseImageUrl(111) },
        
        { title: "Inovasi Pelayanan Kesehatan Keliling di Pelosok Siak", date: "12 Okt 2025", category: "Kesehatan", excerpt: "Program Puskesmas Keliling menjangkau desa-desa terpencil, memastikan setiap warga mendapatkan layanan kesehatan yang memadai.", image: baseImageUrl(112) },
        { title: "Sosialisasi Bahaya Narkoba di Sekolah-sekolah", date: "11 Okt 2025", category: "Pendidikan", excerpt: "BNNK Siak bekerjasama dengan Dinas Pendidikan menggelar sosialisasi masif tentang bahaya narkoba bagi generasi muda.", image: baseImageUrl(113) },
        { title: "Revitalisasi Pasar Tradisional Bunga Raya", date: "10 Okt 2025", category: "Ekonomi", excerpt: "Pasar tradisional di Bunga Raya direvitalisasi menjadi lebih bersih dan modern tanpa menghilangkan suasana khasnya.", image: baseImageUrl(114) },
        { title: "Siak Tingkatkan Pengawasan Penerimaan Pegawai Pemerintah", date: "09 Okt 2025", category: "Pemerintahan", excerpt: "Pemerintah berkomitmen untuk menciptakan proses rekrutmen pegawai yang transparan dan bebas dari praktik KKN.", image: baseImageUrl(115) },
        { title: "Pelestarian Tenun Siak Melalui Generasi Muda", date: "08 Okt 2025", category: "Budaya", excerpt: "Dinas Kebudayaan mengadakan workshop menenun gratis bagi pelajar SMA untuk melestarikan warisan Tenun Siak.", image: baseImageUrl(116) },
        { title: "Kejuaraan Sepeda Gunung Bupati Cup Siak Sukses Digelar", date: "07 Okt 2025", category: "Olahraga", excerpt: "Ratusan peserta dari berbagai daerah memeriahkan Kejuaraan Sepeda Gunung tahunan yang diadakan di kawasan hutan lindung.", image: baseImageUrl(117) },
        { title: "Penerapan Sistem Smart City untuk Efisiensi Layanan", date: "06 Okt 2025", category: "Pemerintahan", excerpt: "Siak mulai menerapkan berbagai modul Smart City untuk meningkatkan efisiensi dan transparansi dalam layanan publik.", image: baseImageUrl(118) },
        { title: "Edukasi Mitigasi Kebakaran Hutan dan Lahan", date: "05 Okt 2025", category: "Bencana", excerpt: "Tim gabungan terus memberikan edukasi kepada masyarakat tentang pencegahan dan penanganan dini kebakaran hutan dan lahan (Karhutla).", image: baseImageUrl(119) },
        { title: "Pemerintah Beri Bantuan Bibit Unggul Sawit ke Petani", date: "04 Okt 2025", category: "Pertanian", excerpt: "Ribuan bibit sawit unggul dibagikan secara gratis kepada petani kecil untuk peremajaan kebun dan peningkatan produksi.", image: baseImageUrl(120) },
        { title: "Pengembangan Dermaga Wisata Pelabuhan Lama Siak", date: "03 Okt 2025", category: "Pariwisata", excerpt: "Proyek pengembangan dermaga lama menjadi pusat wisata tepian sungai yang dilengkapi dengan kafe dan pusat oleh-oleh.", image: baseImageUrl(121) },
        { title: "Sosialisasi Pajak Daerah untuk Peningkatan PAD", date: "02 Okt 2025", category: "Ekonomi", excerpt: "Bapenda Siak mengadakan sosialisasi intensif mengenai kewajiban pajak daerah untuk meningkatkan Pendapatan Asli Daerah (PAD).", image: baseImageUrl(122) },
        { title: "Pemberdayaan Wanita Melalui Kerajinan Eceng Gondok", date: "01 Okt 2025", category: "Pemberdayaan", excerpt: "Kelompok ibu-ibu di desa mulai memproduksi kerajinan bernilai jual tinggi dari bahan baku eceng gondok di sungai.", image: baseImageUrl(123) },

        { title: "Lomba Inovasi Teknologi Tepat Guna Tingkat Kabupaten", date: "30 Sep 2025", category: "Teknologi", excerpt: "Lomba tahunan ini menjaring ide-ide kreatif dari masyarakat untuk solusi permasalahan sehari-hari.", image: baseImageUrl(124) },
        { title: "Optimalisasi Pelayanan di Mal Pelayanan Publik (MPP) Siak", date: "29 Sep 2025", category: "Pemerintahan", excerpt: "MPP terus berupaya meningkatkan kualitas layanan dengan penambahan loket dan integrasi sistem.", image: baseImageUrl(125) },
        { title: "Program Beasiswa Pendidikan Unggulan Kabupaten Siak", date: "28 Sep 2025", category: "Pendidikan", excerpt: "Pemerintah kembali membuka program beasiswa bagi pelajar berprestasi untuk melanjutkan pendidikan ke jenjang yang lebih tinggi.", image: baseImageUrl(126) },
        { title: "Peluncuran Program Siak Hijau dan Bersih", date: "27 Sep 2025", category: "Lingkungan", excerpt: "Sebuah program komprehensif diluncurkan untuk mengurangi sampah plastik dan meningkatkan ruang terbuka hijau.", image: baseImageUrl(127) },
        { title: "Kunjungan Investor Asing untuk Sektor Agrowisata", date: "26 Sep 2025", category: "Pariwisata", excerpt: "Delegasi investor dari Asia Tenggara mengunjungi Siak untuk menjajaki potensi investasi di bidang agrowisata.", image: baseImageUrl(128) },
        { title: "Upaya Pencegahan Penyebaran Demam Berdarah (DBD)", date: "25 Sep 2025", category: "Kesehatan", excerpt: "Fogging dan gerakan 3M plus gencar dilakukan oleh Dinas Kesehatan untuk menekan kasus Demam Berdarah.", image: baseImageUrl(129) },
    ];

    // --- Fungsi Rendering ---
    function renderNews(newsToRender) {
        newsContainer.innerHTML = '';
        
        if (newsToRender.length === 0) {
            newsContainer.style.display = 'none';
            noResults.style.display = 'block';
            return;
        }

        newsContainer.style.display = 'grid';
        noResults.style.display = 'none';

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const currentItems = newsToRender.slice(start, end);

        currentItems.forEach(news => {
        const newsItem = document.createElement('a'); // Tetap menggunakan <a> agar seluruh kartu bisa di-klik
        newsItem.href = newsDetailUrl + '?title=' + encodeURIComponent(news.title); 
        newsItem.className = 'news-item';
        newsItem.innerHTML = `
            <img src="${news.image}" alt="Gambar ${news.title}" onerror="this.onerror=null;this.src='https://picsum.photos/seed/default/400/180';">
            <div class="news-content">
            <div class="news-meta">
                <span><i class="fas fa-calendar-alt"></i> ${news.date}</span>
                <span><i class="fas fa-tag"></i> ${news.category}</span>
            </div>
            <h3>${news.title}</h3>
            <p class="news-excerpt">${news.excerpt}</p>
            </div>
        `;
        newsContainer.appendChild(newsItem);
        });
        
        renderPagination(newsToRender.length);
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        paginationControls.innerHTML = '';

        if (totalPages <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => window.changePage(currentPage - 1);
        paginationControls.appendChild(prevBtn);

        const maxPageButtons = 5; 
        let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);

        if (endPage - startPage + 1 < maxPageButtons) {
            startPage = Math.max(1, endPage - maxPageButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.textContent = i;
        if (i === currentPage) {
            pageBtn.classList.add('active');
        }
        pageBtn.onclick = () => window.changePage(i);
        paginationControls.appendChild(pageBtn);
        }
        
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => window.changePage(currentPage + 1);
        paginationControls.appendChild(nextBtn);
    }

    // --- Fungsi Kontrol ---
    window.changePage = function(page) {
        if (page < 1 || page > Math.ceil(filteredNews.length / itemsPerPage)) return;
        currentPage = page;
        renderNews(filteredNews);
        window.scrollTo({ top: newsContainer.offsetTop - 100, behavior: 'smooth' });
    }

    window.filterNews = function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        
        filteredNews = allNewsData.filter(news => {
            return news.title.toLowerCase().includes(searchTerm) || 
                news.excerpt.toLowerCase().includes(searchTerm);
        });

        currentPage = 1; 
        renderNews(filteredNews);
    }

    // --- Inisialisasi ---
    filteredNews = allNewsData;
    renderNews(filteredNews);

    })();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>