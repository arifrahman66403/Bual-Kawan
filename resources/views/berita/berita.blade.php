<x-breadcrumb title="Berita">
  <li class="breadcrumb-item" aria-hidden="false">
    <i class="fas fa-newspaper"></i>
    <strong style="margin-left:6px">Berita</strong>
    </li>
</x-breadcrumb>
<x-layout title="Berita">
  <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Cari berita berdasarkan judul atau isi..." onkeyup="window.filterNews()" />
        <button onclick="window.filterNews()"><i class="fas fa-search"></i> Cari</button>
    </div>

    <div id="news-container" class="news-grid">
        </div>

    <div id="no-results" class="no-results" style="display:none;">
        <i class="fas fa-exclamation-circle"></i> Tidak ada berita yang ditemukan.
    </div>

    <div id="pagination-controls" class="pagination">
        </div>

    <section class="content-section" aria-labelledby="beritaTitle">
    <h2 id="beritaTitle" style="margin:8px 0 12px">Berita Terbaru</h2>
    <div class="berita-list">
      <article class="berita-item">
        <h3><a href="#">Judul Berita Pertama</a></h3>
        <p class="berita-meta">Dipublikasikan pada 1 Januari 2024</p>
        <p>Ringkasan singkat dari berita pertama. Ini adalah deskripsi singkat yang memberikan gambaran tentang isi berita.</p>
      </article>
      <article class="berita-item">
        <h3><a href="#">Judul Berita Kedua</a></h3>
        <p class="berita-meta">Dipublikasikan pada 15 Januari 2024</p>
        <p>Ringkasan singkat dari berita kedua. Ini adalah deskripsi singkat yang memberikan gambaran tentang isi berita.</p>
      </article>
      <article class="berita-item">
        <h3><a href="#">Judul Berita Ketiga</a></h3>
        <p class="berita-meta">Dipublikasikan pada 28 Januari 2024</p>
        <p>Ringkasan singkat dari berita ketiga. Ini adalah deskripsi singkat yang memberikan gambaran tentang isi berita.</p>
      </article>
    </div>
  </section>
</x-layout>