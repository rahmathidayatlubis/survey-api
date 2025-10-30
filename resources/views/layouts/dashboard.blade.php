<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Survey</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --text-muted: #94a3b8;
            --accent: #06b6d4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #020617 100%);
            color: white;
            min-height: 100vh;
            box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, transparent, rgba(99, 102, 241, 0.3), transparent);
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            to {
                left: 100%;
            }
        }

        .sidebar-brand img {
            max-width: 50px;
            height: auto;
            filter: drop-shadow(0 2px 8px rgba(99, 102, 241, 0.3));
            transition: transform 0.3s ease;
        }

        .sidebar-brand:hover img {
            transform: scale(1.05);
        }

        .sidebar-menu {
            padding: 24px 0;
        }

        .menu-section-title {
            padding: 8px 24px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-top: 16px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            margin: 3px 12px;
            border-radius: 0 8px 8px 0;
            position: relative;
            overflow: hidden;
        }

        .sidebar a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15), transparent);
            transition: width 0.3s ease;
        }

        .sidebar a:hover::before {
            width: 100%;
        }

        .sidebar a i {
            width: 24px;
            margin-right: 14px;
            font-size: 1.15rem;
            transition: all 0.3s ease;
        }

        .sidebar a span {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar a:hover {
            background: rgba(99, 102, 241, 0.1);
            border-left-color: var(--primary);
            color: #fff;
            padding-left: 28px;
            transform: translateX(2px);
        }

        .sidebar a:hover i {
            transform: scale(1.1);
            color: var(--accent);
        }

        .sidebar a.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.05));
            border-left-color: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .sidebar a.active i {
            color: var(--accent);
        }

        /* Content Area */
        .content-area {
            flex: 1;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 20px 36px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .clock-container {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(99, 102, 241, 0.2);
            transition: all 0.3s ease;
        }

        .clock-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .clock-icon {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .clock-time {
            display: flex;
            flex-direction: column;
            color: white;
        }

        .time-display {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .date-display {
            font-size: 0.65rem;
            font-weight: 500;
            opacity: 0.9;
            margin-top: -2px;
        }

        .header .user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header .avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .header .avatar:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .dropdown-toggle {
            font-weight: 600;
            color: #1e293b !important;
            transition: all 0.2s ease;
        }

        .dropdown-toggle:hover {
            color: var(--primary) !important;
        }

        .dropdown-toggle::after {
            margin-left: 10px;
        }

        .user-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        .user-role {
            display: inline-block;
            padding: 3px 10px;
            background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
            color: var(--primary);
            border-radius: 16px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            border-radius: 12px;
            margin-top: 12px;
            padding: 8px;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-divider {
            margin: 8px 0;
            opacity: 0.1;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 36px;
        }

        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 24px;
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        footer strong {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .heart-icon {
            color: #ef4444;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar-brand img {
                max-width: 40px;
            }
            
            .sidebar a span,
            .menu-section-title {
                display: none;
            }
            
            .sidebar a {
                justify-content: center;
                padding: 14px;
                margin: 3px 8px;
            }
            
            .sidebar a i {
                margin-right: 0;
            }

            .clock-container {
                padding: 5px 10px;
            }

            .time-display {
                font-size: 0.9rem;
            }

            .date-display {
                font-size: 0.6rem;
            }

            .user-name {
                display: none;
            }
        }

        /* Loading Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-area {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/image.png') }}" alt="Logo">
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-section-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" 
            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard Utama</span>
            </a>

            <a href="{{ route('admin.user') }}" 
            class="{{ request()->routeIs('admin.user') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Data User</span>
            </a>

            <a href="{{ route('admin.survey') }}" 
            class="{{ request()->routeIs('admin.survey') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Data Survey</span>
            </a>
            
            <div class="menu-section-title">Lainnya</div>
            <a href="{{ route('admin.laporan') }}" 
            class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Data Laporan</span>
            </a>

            <a href="{{ route('admin.hasil') }}" 
            class="{{ request()->routeIs('admin.hasil') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Data Hasil</span>
            </a>
        </div>
    </div>

    {{-- Content Wrapper --}}
    <div class="content-area">

        {{-- Header --}}
        <div class="header d-flex justify-content-between align-items-center">
            <div class="clock-container">
                <i class="fas fa-clock clock-icon"></i>
                <div class="clock-time">
                    <div class="time-display" id="clock">00:00:00</div>
                    <div class="date-display" id="date">Loading...</div>
                </div>
            </div>
            
            @auth
            <div class="user-profile">
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="d-none d-md-block">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <span class="user-role">{{ Auth::user()->role }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i> Profile Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-bell me-2"></i> Notifikasi
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Pengaturan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button id="logout-btn" class="dropdown-item text-danger" type="button">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>

                        </li>
                    </ul>
                </div>
            </div>
            @endauth
        </div>

        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer>
            <div>
              
                Created by <strong>Back End Developer - PT Siantar Codes Academy</strong> © {{ date('Y') }}
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', options);

            document.getElementById('clock').textContent = timeString;
            document.getElementById('date').textContent = dateString;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // ✅ SweetAlert2 Konfirmasi Logout
        document.addEventListener('DOMContentLoaded', function () {
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        text: "Kamu akan keluar dari sistem!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal',
                        background: '#f8fafc',
                        customClass: {
                            popup: 'rounded-4 shadow-lg',
                            confirmButton: 'px-4 py-2',
                            cancelButton: 'px-4 py-2'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                });
            }
        });
    </script>


    <script>
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            
            // Format waktu
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            
            // Format tanggal
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', options);
            
            document.getElementById('clock').textContent = timeString;
            document.getElementById('date').textContent = dateString;
        }
        
        // Update setiap detik
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>