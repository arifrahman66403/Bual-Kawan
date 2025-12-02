<x-layout-admin title="Akun Anda Dinonaktifkan">
    <div class="container text-center py-5">
        <h1 class="display-4 text-danger">
        <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
            style="width: 90px; height: 90px; background: #ffe5e5;">
            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
        </div>
            Akun Dinonaktifkan
        </h1>
        <p class="text-center text-muted">
            Mohon maaf, akun Anda telah dinonaktifkan oleh Administrator.
        </p>
        <p class="text-center text-muted">
            Jika Anda merasa ini merupakan kesalahan atau ingin mengajukan banding,
            silakan hubungi tim dukungan kami.
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
    </div>
</x-layout-admin>
