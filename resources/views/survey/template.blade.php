<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Survey</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: #2d3748;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #667eea;
        }

        .menu-item {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-item:hover {
            background: #4a5568;
        }

        .menu-item.active {
            background: #667eea;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #2d3748;
            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #718096;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
        }

        .stat-change {
            font-size: 14px;
            margin-top: 8px;
            color: #48bb78;
        }

        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .chart-container h2 {
            color: #2d3748;
            margin-bottom: 20px;
        }

        .survey-list {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .survey-item {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .survey-item:last-child {
            border-bottom: none;
        }

        .survey-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .survey-meta {
            font-size: 14px;
            color: #718096;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-closed {
            background: #fed7d7;
            color: #742a2a;
        }
        .sidebar a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    margin-bottom: 8px;
    border-radius: 8px;
    transition: all 0.3s;
}

.sidebar a:hover {
    background: #4a5568;
}

.sidebar a.active {
    background: #667eea;
}

        
    </style>
</head>
<body>
   <div class="sidebar">
    <div class="logo">📊 SurveyHub</div>

    <a href="{{ url('/dashboard') }}" 
        class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
        <span>📈</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ url('/surveys') }}" 
        class="menu-item {{ Request::is('surveys*') ? 'active' : '' }}">
        <span>📝</span>
        <span>Survey Saya</span>
    </a>

    <a href="{{ url('/survey/create') }}" 
       class="menu-item {{ Request::is('survey/create') ? 'active' : '' }}">
        <span>➕</span>
        <span>Buat Survey</span>
    </a>

    <a href="{{ url('/respondents') }}" 
       class="menu-item {{ Request::is('respondents*') ? 'active' : '' }}">
        <span>👥</span>
        <span>Responden</span>
    </a>

    <a href="{{ url('/reports') }}" 
       class="menu-item {{ Request::is('reports*') ? 'active' : '' }}">
        <span>📊</span>
        <span>Laporan</span>
    </a>

    <a href="{{ url('/settings') }}" 
        class="menu-item {{ Request::is('settings*') ? 'active' : '' }}">
        <span>⚙</span>
        <span>Pengaturan</span>
    </a>
</div>


    @yield('content')

    {{-- <script>
        const ctx = document.getElementById('responseChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ago', 'Sep', 'Okt'],
                datasets: [{
                    label: 'Jumlah Responden',
                    data: [120, 150, 180, 220, 190, 250, 280, 310, 290, 352],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script> --}}
</body>
</html>