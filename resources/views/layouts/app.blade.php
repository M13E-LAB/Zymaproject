<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZYMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #000000;
            --bg-secondary: #121212;
            --bg-tertiary: #1E1E1E;
            --accent-primary: #00D1B2;
            --accent-gradient: linear-gradient(45deg, #00D1B2, #00F2C3);
            --accent-secondary: #666666;
            --text-primary: #FFFFFF;
            --text-secondary: #A0A0A0;
            --card-bg: rgba(255, 255, 255, 0.03);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--bg-secondary);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary) !important;
            letter-spacing: -1px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            margin: 0 auto;
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: transparent;
        }

        .nav-link:hover {
            background: var(--card-bg);
            transform: translateY(-2px);
        }

        .nav-link i {
            color: var(--accent-primary);
            margin-right: 0.5rem;
        }
        
        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-primary);
        }
        
        .dropdown-menu {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .dropdown-item {
            color: var(--text-primary);
            padding: 0.8rem 1.5rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--bg-tertiary);
            color: var(--accent-primary);
        }
        
        .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
            color: var(--bg-primary);
        }
        
        .user-points {
            background-color: var(--accent-primary);
            color: var(--bg-primary);
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
        }

        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            color: var(--bg-primary);
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 209, 178, 0.2);
            opacity: 0.9;
        }

        .text-success {
            color: var(--accent-primary) !important;
        }

        .product-image {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 24px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .product-details {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .price-stat-item {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 24px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-stat-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
        }

        .price-stat-item i {
            font-size: 2rem;
            color: var(--accent-primary);
            margin-bottom: 1rem;
        }

        .table {
            color: var(--text-primary);
            border-collapse: separate;
            border-spacing: 0 0.8rem;
        }

        .table th {
            border: none;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .table td {
            background: var(--card-bg);
            border: none;
            padding: 1.2rem;
            vertical-align: middle;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .table tr:hover td {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-primary);
            border-radius: 4px;
        }

        /* Alert styling */
        .alert {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: var(--text-primary);
            padding: 1.2rem;
        }

        .alert-info {
            border-left: 4px solid var(--accent-primary);
        }

        main {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        .progress-bar {
            background: var(--accent-gradient);
        }
        
        .badge-item {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--card-bg);
            font-size: 2rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
        }
        
        .badge-item.earned {
            background: var(--accent-gradient);
            color: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0, 209, 178, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="nav-container">
            <a class="navbar-brand" href="/">ZYMA</a>
            <div class="nav-links">
                <a href="{{ route('products.search') }}" class="nav-link">
                    <i class="fas fa-search"></i> Rechercher
                </a>
                <a href="{{ route('statistics') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-stream"></i> Feed Social
                </a>
            </div>
            
            <div class="d-flex align-items-center">
                @if(auth()->check())
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="avatar-small me-2">
                            @else
                                <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                            @endif
                            {{ auth()->user()->name }}
                            <span class="user-points">{{ auth()->user()->points ?? 0 }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle me-2"></i> Mon profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.posts') }}"><i class="fas fa-share-alt me-2"></i> Mes partages</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.points') }}"><i class="fas fa-star me-2"></i> Mes points</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-sign-in-alt me-1"></i> Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i> Inscription
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
