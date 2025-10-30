<x-layout title="Admin Dashboard">
  <span class="navbar-brand">Admin Dashboard</span>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-outline-light">Logout</button>
  </form>

  <div class="container mt-5">
    <h2>Selamat datang, {{ Auth::user()->nama }}</h2>
    <p>Ini adalah halaman dashboard admin.</p>
  </div>
</x-layout>
