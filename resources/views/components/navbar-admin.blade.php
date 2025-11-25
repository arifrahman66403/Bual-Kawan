<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <img src="https://bualkawan.siakkab.go.id/logo-bualkawan2.png" alt="Bual Kawan Logo" height="30" class="me-2">
            <span class="text-color">Bual Kawan</span>
        </a>
        
        <div class="d-flex align-items-center ms-auto">
            <button class="btn btn-sm btn-outline-secondary border-0 me-3" id="darkModeToggle" title="Ganti Mode">
                <i class="bi bi-sun" id="modeIcon"></i>
            </button>

            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-md-inline me-2 text-color fw-semibold">Halo, {{ Auth::user()->nama }}!</span>
                    <img src="https://i.pravatar.cc/150?img=68" class="rounded-circle avatar-sm" alt="Admin Avatar">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" 
                        href="{{ route('admin.profile') }}">
                            <i class="bi bi-person-circle me-2"></i> Profil Pribadi
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>