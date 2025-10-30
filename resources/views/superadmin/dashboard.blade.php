<x-layout>
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">👋 Superadmin Dashboard</h1>
        <p>Akses Anda: Penuh. Fokus: Manajemen Akun dan Sistem.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white shadow"><div class="card-body">Total Akun Admin: {{ $totalAdmins ?? 0 }}</div></div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white shadow"><div class="card-body">Total Akun Operator: {{ $totalOperators ?? 0 }}</div></div>
            </div>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-primary">Kelola User</a>
    </div>
</x-layout>