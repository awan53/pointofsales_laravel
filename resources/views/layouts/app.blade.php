<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem POS | @yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (AdminLTE uses this) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
       /* ==== SIDEBAR ==== */
.sidebar {
    width: 250px;
    background-color: #343a40;
    color: #c2c7d0;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    padding-top: 60px;
    transition: all 0.3s ease;
    overflow-y: auto;
}

.sidebar.closed {
    margin-left: -250px;
}

/* MOBILE MODE - sidebar hidden by default */
@media (max-width: 768px) {
    .sidebar {
        margin-left: -250px;
        position: fixed;
        z-index: 2000;
    }
    .sidebar.open {
        margin-left: 0 !important;
    }
}

/* BRAND */
.sidebar .brand {
    position: fixed;
    top: 0;
    width: 250px;
    height: 56px;
    background-color: #23272b;
    color: white;
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-items: center;
    padding: 0 15px;
    z-index: 2100;
}

/* MAIN WRAPPER */
.main-wrapper {
    flex: 1;
    margin-left: 250px;
    padding: 20px;
    transition: all 0.3s ease;
}

.main-wrapper.full {
    margin-left: 0;
}

/* MOBILE: main wrapper always full width */
@media (max-width: 768px) {
    .main-wrapper {
        margin-left: 0 !important;
    }
}

/* Overlay for mobile */
#sidebarOverlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1500;
}

#sidebarOverlay.show {
    display: block;
}

#sidebarToggle {
    margin-top: 15px;
    margin-left: 10px;
}


    </style>

</head>
<body>
    
  <!-- SIDEBAR (jangan di dalam overlay) -->
    <aside id="sidebar" class="sidebar">

        <div class="brand">
            <i class="fa-solid fa-store me-2"></i> POS System
        </div>

        <nav class="mt-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
                <i class="fa-solid fa-gauge me-2"></i> Dashboard
            </a>
        </li>

        {{-- MENU KHUSUS ADMIN & SUPPLY CHAIN --}}
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'supply_chain')
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link">
                    <i class="fa-solid fa-list me-2"></i> Manajemen Kategori
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fa-solid fa-box me-2"></i> Manajemen Produk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('supplier.index') }}" class="nav-link">
                    <i class="fa-solid fa-truck me-2"></i> Supplier
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchases.index') }}" class="nav-link">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Purchase
                </a>
            </li>
        @endif

        {{-- MENU KHUSUS ADMIN & KASIR --}}
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kasir')
            <li class="nav-item">
                <a href="{{ route('sales.index') }}" class="nav-link">
                    <i class="fa-solid fa-cash-register me-2"></i> Transaksi
                </a>
            </li>
        @endif

        {{-- MENU KHUSUS ADMIN --}}
        @if(auth()->user()->role == 'admin')
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link">
                    <i class="fa-solid fa-file-invoice-dollar me-2"></i> Report
                </a>
            </li>

            <li class="nav-item">
                <a href="#settingMenu" class="nav-link" data-bs-toggle="collapse">
                    <i class="fa-solid fa-gears me-2"></i> Pengaturan
                    <i class="fa-solid fa-angle-left float-end"></i>
                </a>
                <ul class="collapse nav flex-column ms-3" id="settingMenu">
                    <li class="nav-item">
                        {{-- HUBUNGKAN KE ROUTE USER YANG KITA BUAT --}}
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="fa-regular fa-circle me-2"></i> User Management
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <hr class="text-secondary">

        {{-- TOMBOL LOGOUT --}}
        <li class="nav-item mt-auto">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent text-danger w-100 text-start">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>

    </aside>

    <!-- Overlay (berdiri sendiri) -->
    <div id="sidebarOverlay"></div>

    <!-- MAIN -->
    <main id="mainWrapper" class="main-wrapper">
        <button id="sidebarToggle" class="btn btn-dark mb-3">
            <i class="fa-solid fa-bars"></i>
        </button>
        <br>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

  

<script>
    const sidebar = document.getElementById("sidebar");
const main = document.getElementById("mainWrapper");
const toggle = document.getElementById("sidebarToggle");
const overlay = document.getElementById("sidebarOverlay");

toggle.addEventListener("click", () => {
    if (window.innerWidth <= 768) {
        // MOBILE MODE
        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
    } else {
        // DESKTOP MODE
        sidebar.classList.toggle("closed");
        main.classList.toggle("full");
    }
});

overlay.addEventListener("click", () => {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
