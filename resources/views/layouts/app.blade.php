<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZYMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #000000;
            --bg-secondary: #121212;
            --bg-tertiary: #1E1E1E;
            --accent-primary: #000000;
            --accent-gradient: #000000;
            --accent-secondary: #666666;
            --text-primary: #FFFFFF;
            --text-secondary: #A0A0A0;
            --card-bg: rgba(255, 255, 255, 0.03);
            --button-radius: 500px;
            --button-padding: 16px 32px;
            --search-height: 40px;
            --purple-light: #9B82F7;
            --purple-dark: #7253F6;
            --purple-gradient: linear-gradient(135deg, var(--purple-light), var(--purple-dark));
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            font-weight: 500;
        }

        .navbar {
            background-color: var(--bg-primary);
            padding: 1.2rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary) !important;
            letter-spacing: -0.5px;
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
            gap: 1rem;
            margin: 0 auto;
            align-items: center;
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: var(--button-radius);
            transition: all 0.3s ease;
            background: transparent;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: var(--card-bg);
            transform: translateY(-2px);
        }

        .nav-link i {
            margin-right: 0.5rem;
        }
        
        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .dropdown-menu {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .dropdown-item {
            color: var(--text-primary);
            padding: 0.8rem 1.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: var(--bg-tertiary);
        }
        
        .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.9);
            color: var(--text-primary);
            transition: all 0.3s ease;
            border-radius: var(--button-radius);
            padding: var(--button-padding);
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.8);
        }
        
        .btn-outline-light:hover {
            background-color: rgba(0, 0, 0, 0.9);
            border-color: rgba(255, 255, 255, 1);
            color: var(--text-primary);
        }
        
        .user-points {
            background-color: #000;
            color: var(--text-primary);
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-left: 8px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background-color: white;
            border: none;
            color: black;
            font-weight: 600;
            padding: var(--button-padding);
            border-radius: var(--button-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 0px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
        }

        .btn-secondary {
            background-color: rgba(0, 0, 0, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.9);
            color: white;
            font-weight: 600;
            padding: var(--button-padding);
            border-radius: var(--button-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 0px;
        }

        .btn-secondary:hover {
            background-color: rgba(0, 0, 0, 0.9);
            border-color: rgba(255, 255, 255, 1);
            color: white;
        }
        
        .btn-purple {
            background: var(--purple-gradient);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: var(--button-radius);
            transition: all 0.3s ease;
            letter-spacing: 0px;
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            height: var(--search-height);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(114, 83, 246, 0.3);
        }

        .text-success {
            color: var(--text-primary) !important;
        }

        .product-image {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 16px;
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
            border-radius: 16px;
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
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-stat-item:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .price-stat-item i {
            font-size: 2rem;
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
            background: rgba(255, 255, 255, 0.2);
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
            border-left: 4px solid rgba(255, 255, 255, 0.5);
        }

        main {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        .progress-bar {
            background: #ffffff;
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
            background: #000000;
            color: var(--text-primary);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            border: 2px solid white;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        /* Barre de recherche de produits */
        .search-product-form {
            position: relative;
            margin: 0 0.5rem;
        }
        
        .search-product-input {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--button-radius);
            color: var(--text-primary);
            height: var(--search-height);
            padding: 0 1rem 0 2.5rem;
            width: 220px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .search-product-input:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            width: 300px;
            outline: none;
            box-shadow: 0 0 0 2px rgba(114, 83, 246, 0.2);
        }
        
        .search-product-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }
        
        .search-product-form button {
            position: absolute;
            right: 0;
            top: 0;
            height: var(--search-height);
        }
        
        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 0.5rem;
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }
        
        .autocomplete-item {
            padding: 0.8rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .autocomplete-item:hover {
            background-color: var(--bg-tertiary);
        }
        
        .autocomplete-item:last-child {
            border-bottom: none;
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
                
                <form action="{{ route('products.searchByName') }}" method="GET" class="search-product-form" id="searchProductForm">
                    <span class="search-product-icon">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="query" class="search-product-input" id="productSearchInput" 
                           placeholder="Nom du produit" autocomplete="off">
                    <div class="autocomplete-results" id="autocompleteResults"></div>
                </form>
                
                <a href="{{ route('statistics') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </a>
                <a href="{{ route('social.feed') }}" class="nav-link">
                    <i class="fas fa-stream"></i> Communauté
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
                    <a href="{{ route('login') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-sign-in-alt me-1"></i> Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('productSearchInput');
            const autocompleteResults = document.getElementById('autocompleteResults');
            
            if (searchInput && autocompleteResults) {
                // Afficher/cacher les résultats d'autocomplétion
                searchInput.addEventListener('focus', function() {
                    if (this.value.length >= 2) {
                        autocompleteResults.style.display = 'block';
                    }
                });
                
                // Détecter les clics en dehors de la zone de recherche
                document.addEventListener('click', function(event) {
                    if (!searchInput.contains(event.target) && !autocompleteResults.contains(event.target)) {
                        autocompleteResults.style.display = 'none';
                    }
                });
                
                // Fonction d'autocomplétion
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    if (query.length >= 2) {
                        // Requête AJAX pour récupérer les suggestions
                        fetch(`/api/products/search?query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                autocompleteResults.innerHTML = '';
                                
                                if (data.length > 0) {
                                    data.forEach(product => {
                                        const item = document.createElement('div');
                                        item.className = 'autocomplete-item';
                                        item.textContent = product.name;
                                        
                                        item.addEventListener('click', function() {
                                            searchInput.value = product.name;
                                            document.getElementById('searchProductForm').submit();
                                        });
                                        
                                        autocompleteResults.appendChild(item);
                                    });
                                    
                                    autocompleteResults.style.display = 'block';
                                } else {
                                    autocompleteResults.style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Erreur lors de la recherche :', error);
                            });
                    } else {
                        autocompleteResults.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
