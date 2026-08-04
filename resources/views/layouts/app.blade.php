<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SR Wood Craft')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* RESET & BASE */
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; }
        a { text-decoration: none; }
        
        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1a1a2e;
            z-index: 1040;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        .sidebar .brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar .brand h4 {
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            margin: 0;
        }
        .sidebar .brand small {
            color: #a8b2d1;
            font-size: 12px;
        }
        
        .sidebar .nav-section {
            padding: 16px 12px 8px 20px;
            color: #6b7a9a;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .sidebar .nav-link {
            color: #a8b2d1;
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            font-size: 14px;
            position: relative;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(100,255,218,0.08);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(100,255,218,0.12);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 15px;
            text-align: center;
        }
        .sidebar .nav-link .badge-count {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        .sidebar .nav-link .badge-warning {
            margin-left: auto;
            background: #f59e0b;
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        /* SIDEBAR - USER INFO */
        .sidebar .user-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: #1a1a2e;
        }
        .sidebar .user-info .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }
        .sidebar .user-info .name {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
        }
        .sidebar .user-info .role {
            color: #a8b2d1;
            font-size: 12px;
        }
        .sidebar .user-info .logout-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            transition: all 0.3s;
        }
        .sidebar .user-info .logout-btn:hover {
            color: #dc2626;
            transform: scale(1.1);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }
        .main-content .header {
            background: #fff;
            padding: 12px 24px;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .main-content .header h5 {
            font-weight: 700;
            margin: 0;
            color: #1a1a2e;
        }
        .main-content .content {
            padding: 24px;
        }

        /* CARDS */
        .card-custom {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 16px 20px;
            transition: all 0.3s;
        }
        .card-custom:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            border-left: 4px solid #1a1a2e;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .stat-card .icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* BADGES */
        .badge-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-menunggu { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-sebagian { background: #fef3c7; color: #92400e; }
        .badge-draft { background: #e5e7eb; color: #374151; }
        .badge-diverifikasi { background: #d1fae5; color: #065f46; }

        /* TABLES */
        .table-custom {
            font-size: 14px;
            margin-bottom: 0;
        }
        .table-custom thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            padding: 10px 14px;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }
        .table-custom tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-custom tbody tr:hover {
            background: #f8fafc;
        }

        /* BUTTONS */
        .btn-primary-custom {
            background: #1a1a2e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: #2d2d4a;
            color: #fff;
            transform: translateY(-2px);
        }
        .btn-success-custom {
            background: #22c55e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-success-custom:hover {
            background: #16a34a;
            color: #fff;
        }
        .btn-danger-custom {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-danger-custom:hover {
            background: #dc2626;
            color: #fff;
        }

        /* FORMS */
        .form-control-sm,
        .form-select-sm {
            font-size: 13px;
            border-radius: 8px;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #1a1a2e;
            box-shadow: 0 0 0 3px rgba(26,26,46,0.1);
        }

        /* NOTIFICATION */
        #notificationDropdown {
            width: 340px;
            max-height: 420px;
            overflow-y: auto;
            position: absolute;
            right: 0;
            top: 42px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            display: none;
            z-index: 1050;
            border: 1px solid #e5e7eb;
        }
        #notificationDropdown .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            font-size: 14px;
            color: #1a1a2e;
        }
        .notification-item {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1a1a2e;
            font-size: 13px;
            transition: all 0.2s;
        }
        .notification-item:hover {
            background: #f8fafc;
        }
        .notification-item .notif-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }
        .notification-item .notif-text {
            flex: 1;
        }
        .notification-item .notif-text .title {
            font-weight: 500;
        }
        .notification-item .notif-text .desc {
            font-size: 12px;
            color: #6b7280;
        }
        #notificationBtn {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            transition: all 0.3s;
            position: relative;
        }
        #notificationBtn:hover {
            background: #f8fafc;
            border-color: #1a1a2e;
        }
        #notificationBtn .badge-notif {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: 0;
            }
            .sidebar .user-info {
                position: relative;
                border-top: 1px solid rgba(255,255,255,0.05);
            }
            .main-content {
                margin-left: 0;
            }
            .main-content .header {
                padding: 10px 16px;
                flex-wrap: wrap;
                gap: 8px;
            }
            .main-content .content {
                padding: 16px;
            }
            .sidebar .nav-link {
                padding: 8px 16px;
                margin: 2px 8px;
                font-size: 13px;
            }
            #notificationDropdown {
                width: 300px;
                right: -80px;
            }
        }

        @media (max-width: 576px) {
            #notificationDropdown {
                width: 280px;
                right: -120px;
            }
        }

        /* ANIMATIONS */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spinner {
            display: inline-block;
            width: 1.5rem;
            height: 1.5rem;
            border: 3px solid #e5e7eb;
            border-top-color: #1a1a2e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* MISC */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .gap-1 { gap: 4px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        
        .border-left-danger { border-left: 4px solid #ef4444; }
        .border-left-success { border-left: 4px solid #22c55e; }
        .border-left-warning { border-left: 4px solid #f59e0b; }
        .border-left-info { border-left: 4px solid #3b82f6; }
        
        .bg-danger-soft { background: #fee2e2; }
        .bg-success-soft { background: #d1fae5; }
        .bg-warning-soft { background: #fef3c7; }
        .bg-info-soft { background: #dbeafe; }
    </style>
</head>
<body>

@auth
<!-- SIDEBAR -->
<div class="sidebar">
    <!-- Brand -->
    <div class="brand">
        <h4> SR Wood Craft</h4>
        <small>Sistem Inventori Bahan Baku</small>
    </div>

    <!-- Menu -->
    <nav>
        @php
            $role = auth()->user()->role;
            $userId = auth()->user()->id;
            
            // Optimasi: Hanya hitung jika role membutuhkan badge tersebut (agar tidak lambat)
            $countPR = 0; $countBM = 0; $countPG = 0;
            if ($role === 'gudang') {
                $countPR = \App\Models\PermintaanProduksiHeader::where('status', 'Menunggu')->count();
                $countBM = \App\Models\BarangMasuk::where('status', 'Draft')->count();
            } elseif ($role === 'pengadaan') {
                $countPG = \App\Models\PermintaanGudangHeader::whereIn('status', ['Diproses', 'Sebagian'])
                    ->whereDoesntHave('barangMasuk', function($q) {
                        $q->where('status', 'Draft');
                    })->count();
            }
        @endphp

        <!-- MENU ADMIN -->
        @if($role == 'admin')
        <div class="nav-section">Main Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Manajemen User
        </a>
        <a href="{{ route('admin.bahan.index') }}" class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Master Bahan
        </a>
        
        <div class="nav-section">Monitoring</div>
        <a href="{{ route('admin.history.index') }}" class="nav-link {{ request()->routeIs('admin.history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Semua Laporan
        </a>
        @endif

        <!-- MENU PRODUKSI -->
        @if($role == 'produksi')
        <div class="nav-section">Main Menu</div>
        <a href="{{ route('produksi.dashboard') }}" class="nav-link {{ request()->routeIs('produksi.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('produksi.permintaan.create') }}" class="nav-link {{ request()->routeIs('produksi.permintaan.*') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> Buat Permintaan
        </a>
        
        <div class="nav-section">Riwayat</div>
        <a href="{{ route('produksi.history.index') }}" class="nav-link {{ request()->routeIs('produksi.history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Permintaan
        </a>
        @endif

        <!-- MENU GUDANG -->
        @if($role == 'gudang')
        <div class="nav-section">Main Menu</div>
        <a href="{{ route('gudang.dashboard') }}" class="nav-link {{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('gudang.stok.index') }}" class="nav-link {{ request()->routeIs('gudang.stok.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Monitoring Stok
        </a>
        
        <div class="nav-section">Permintaan Produksi</div>
        <a href="{{ route('gudang.permintaan-produksi.index') }}" class="nav-link {{ request()->routeIs('gudang.permintaan-produksi.*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Permintaan Masuk
            @if($countPR > 0) <span class="badge-count">{{ $countPR }}</span> @endif
        </a>
        <a href="{{ route('gudang.pengeluaran-history.index') }}" class="nav-link {{ request()->routeIs('gudang.pengeluaran-history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Pengeluaran
        </a>
        
        <div class="nav-section">Pengadaan</div>
        <a href="{{ route('gudang.permintaan-pengadaan.create') }}" class="nav-link {{ request()->routeIs('gudang.permintaan-pengadaan.create') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> Buat Pengadaan
        </a>
        <a href="{{ route('gudang.permintaan-pengadaan-history.index') }}" class="nav-link {{ request()->routeIs('gudang.permintaan-pengadaan-history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Pengadaan
        </a>
        
        <div class="nav-section">Barang Masuk</div>
        <a href="{{ route('gudang.barang-masuk.index') }}" class="nav-link {{ request()->routeIs('gudang.barang-masuk.*') ? 'active' : '' }}">
            <i class="fas fa-arrow-down"></i> Verifikasi Barang
            @if($countBM > 0) <span class="badge-count">{{ $countBM }}</span> @endif
        </a>
        <a href="{{ route('gudang.barang-masuk-history.index') }}" class="nav-link {{ request()->routeIs('gudang.barang-masuk-history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Barang Masuk
        </a>
        @endif

        <!-- MENU PENGADAAN -->
        @if($role == 'pengadaan')
        <div class="nav-section">Main Menu</div>
        <a href="{{ route('pengadaan.dashboard') }}" class="nav-link {{ request()->routeIs('pengadaan.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        
        <div class="nav-section">Permintaan</div>
        <a href="{{ route('pengadaan.permintaan.index') }}" class="nav-link {{ request()->routeIs('pengadaan.permintaan.*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Permintaan Masuk
            @if($countPG > 0) <span class="badge-count">{{ $countPG }}</span> @endif
        </a>
        <a href="{{ route('pengadaan.permintaan-history.index') }}" class="nav-link {{ request()->routeIs('pengadaan.permintaan-history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Permintaan
        </a>
        
        <div class="nav-section">Barang Masuk</div>
        <a href="{{ route('pengadaan.barang-masuk-history.index') }}" class="nav-link {{ request()->routeIs('pengadaan.barang-masuk-history.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Laporan Input Barang
        </a>
        @endif
    </nav>

    <!-- User Info -->
    <div class="user-info">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</div>
            <div class="flex-grow-1">
                <div class="name">{{ auth()->user()->nama_lengkap }}</div>
                <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
        <h5>@yield('page-title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-3">
            <!-- Notifikasi -->
            <div class="position-relative" id="notificationContainer">
                <button type="button" id="notificationBtn" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="badge-notif" id="notificationBadge" style="display: none;">0</span>
                </button>
                <div id="notificationDropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-bell me-2"></i> Notifikasi
                    </div>
                    <div id="notificationList">
                        <div class="text-center text-muted py-4" style="font-size: 13px;">
                            <i class="fas fa-check-circle me-1"></i> Tidak ada notifikasi
                        </div>
                    </div>
                </div>
            </div>
            <span class="text-muted" style="font-size: 13px; white-space: nowrap;">
                <i class="far fa-calendar-alt me-1"></i> <span id="currentDate">{{ date('d F Y') }}</span>
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// NOTIFIKASI REAL-TIME
function fetchNotifikasi() {
    fetch('{{ route("ajax.notifikasi") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotifikasi(data);
            }
        })
        .catch(error => console.error('Error fetching notifikasi:', error));
}

function updateNotifikasi(data) {
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');

    if (data.count > 0) {
        badge.textContent = data.count;
        badge.style.display = 'block';

        let html = '';
        data.data.forEach(item => {
            const colorMap = {
                'danger': '#dc2626',
                'warning': '#f59e0b',
                'info': '#3b82f6',
                'success': '#22c55e',
            };
            const bgColor = colorMap[item.color] || '#6b7280';
            html += `
                <a href="${item.link}" class="notification-item">
                    <div class="notif-icon" style="background: ${bgColor}20; color: ${bgColor};">
                        <i class="fas ${item.icon}"></i>
                    </div>
                    <div class="notif-text">
                        <div class="title">${item.message}</div>
                    </div>
                    <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
            `;
        });
        list.innerHTML = html;
    } else {
        badge.style.display = 'none';
        list.innerHTML = `
            <div class="text-center text-muted py-4" style="font-size: 13px;">
                <i class="fas fa-check-circle me-1"></i> Tidak ada notifikasi
            </div>
        `;
    }
}

// Toggle dropdown notifikasi
document.getElementById('notificationBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    dropdown.classList.add('fade-in');
});

// Tutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    if (!e.target.closest('#notificationContainer')) {
        document.getElementById('notificationDropdown').style.display = 'none';
    }
});

// UPDATE JAM REAL-TIME
function updateClock() {
    const now = new Date();
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
}

// START SERVICES
document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi - setiap 15 detik
    fetchNotifikasi();
    setInterval(fetchNotifikasi, 15000);

    // Jam - setiap 60 detik
    setInterval(updateClock, 60000);
});
</script>
@endauth

@yield('scripts')

</body>
</html>
