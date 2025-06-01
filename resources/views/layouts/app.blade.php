<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            --btn-bg-primary: rgba(15, 15, 15, 0.95);
            --btn-border-blue: #3498DB;
            --btn-text-white: #ffffff;
            --btn-radius: 50px;
            --btn-padding: 14px 28px;
            --btn-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --btn-shadow: 0 4px 16px rgba(52, 152, 219, 0.15);
            --btn-shadow-hover: 0 8px 32px rgba(52, 152, 219, 0.25);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            font-weight: 500;
        }

        .navbar {
            background: rgba(15, 15, 15, 0.95) !important;
            padding: 1.2rem 0;
            border-bottom: 2px solid var(--btn-border-blue);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15);
            position: relative;
            z-index: 10000 !important;
        }

        .navbar-brand {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--btn-text-white) !important;
            letter-spacing: -0.5px;
            transition: var(--btn-transition);
        }

        .navbar-brand:hover {
            color: var(--btn-border-blue) !important;
            transform: scale(1.05);
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
            gap: 0.5rem;
            margin: 0 auto;
            align-items: center;
        }

        .nav-link {
            background: rgba(30, 30, 30, 0.8) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            color: var(--btn-text-white) !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            padding: 12px 24px !important;
            border-radius: var(--btn-radius) !important;
            transition: var(--btn-transition) !important;
            white-space: nowrap !important;
            font-size: 0.95rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            min-height: 44px !important;
            backdrop-filter: blur(10px) !important;
        }

        .nav-link:hover {
            background: var(--btn-bg-primary) !important;
            border-color: var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--btn-shadow) !important;
        }

        .nav-link i {
            margin-right: 0.5rem;
        }
        
        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--btn-border-blue);
        }
        
        .dropdown {
            position: relative;
            z-index: 10001 !important;
        }
        
        .dropdown-menu {
            background: rgba(15, 15, 15, 0.98) !important;
            border: 3px solid var(--btn-border-blue) !important;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(52, 152, 219, 0.4), 
                        0 8px 32px rgba(0, 0, 0, 0.8),
                        inset 0 0 0 1px rgba(52, 152, 219, 0.1);
            backdrop-filter: blur(20px);
            transform: translateY(-10px);
            animation: dropdownSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            min-width: 250px;
            padding: 0.5rem 0;
            z-index: 99999 !important;
        }
        
        @keyframes dropdownSlideIn {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .dropdown-item {
            color: var(--btn-text-white) !important;
            padding: 1rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-weight: 600;
            background: transparent !important;
            border-left: 4px solid transparent;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }
        
        .dropdown-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(52, 152, 219, 0.1)) !important;
            color: var(--btn-border-blue) !important;
            border-left-color: var(--btn-border-blue);
            transform: translateX(8px);
            box-shadow: inset 0 0 20px rgba(52, 152, 219, 0.1);
        }
        
        .dropdown-item:hover::before {
            left: 100%;
        }
        
        .dropdown-item i {
            color: var(--btn-border-blue);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover i {
            transform: scale(1.2) rotate(5deg);
        }
        
        .dropdown-divider {
            border-color: var(--btn-border-blue);
            opacity: 0.5;
            margin: 0.5rem 1rem;
            border-width: 1px;
        }
        
        /* Animation du toggle button */
        .dropdown-toggle {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease !important;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.05), rgba(52, 152, 219, 0.1)) !important;
            border: 2px solid rgba(52, 152, 219, 0.3) !important;
            border-radius: 25px !important;
            animation: userButtonGlow 3s ease-in-out infinite !important;
        }
        
        @keyframes userButtonGlow {
            0%, 100% {
                border-color: rgba(52, 152, 219, 0.3);
                box-shadow: 0 0 10px rgba(52, 152, 219, 0.1);
            }
            50% {
                border-color: rgba(52, 152, 219, 0.6);
                box-shadow: 0 0 20px rgba(52, 152, 219, 0.3);
            }
        }
        
        .dropdown-toggle::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s ease;
        }
        
        .dropdown-toggle:hover::after {
            left: 100%;
        }
        
        .dropdown-toggle:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(52, 152, 219, 0.3)) !important;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4) !important;
            transform: translateY(-3px) scale(1.02) !important;
            border-color: rgba(52, 152, 219, 0.8) !important;
        }
        
        .dropdown-toggle[aria-expanded="true"] {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.3), rgba(52, 152, 219, 0.4)) !important;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.5) !important;
            transform: translateY(-3px) scale(1.02) !important;
            border-color: rgba(52, 152, 219, 1) !important;
        }
        
        .user-points {
            background: linear-gradient(135deg, var(--btn-bg-primary), rgba(52, 152, 219, 0.8));
            color: var(--btn-text-white);
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 0.8rem;
            margin-left: 10px;
            border: 2px solid var(--btn-border-blue);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            animation: pointsPulse 2s infinite;
            transition: all 0.3s ease;
        }
        
        @keyframes pointsPulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            }
            50% {
                box-shadow: 0 6px 25px rgba(52, 152, 219, 0.5);
            }
        }
        
        .dropdown-toggle:hover .user-points {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
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
        
        /* Barre de recherche modernisée */
        .search-product-form {
            position: relative;
            margin: 0 0.5rem;
        }
        
        .search-product-input {
            background: rgba(30, 30, 30, 0.8) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: var(--btn-radius) !important;
            color: var(--btn-text-white) !important;
            height: 44px !important;
            padding: 0 1rem 0 2.5rem !important;
            width: 220px !important;
            transition: var(--btn-transition) !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            backdrop-filter: blur(10px) !important;
        }
        
        .search-product-input:focus {
            background: var(--btn-bg-primary) !important;
            border-color: var(--btn-border-blue) !important;
            width: 300px !important;
            outline: none !important;
            box-shadow: var(--btn-shadow) !important;
            color: var(--btn-text-white) !important;
        }

        .search-product-input::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .search-product-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--btn-border-blue);
            pointer-events: none;
            font-size: 1.1rem;
        }
        
        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 0.5rem;
            background: rgba(15, 15, 15, 0.95);
            border: 2px solid var(--btn-border-blue);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(52, 152, 219, 0.3);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            backdrop-filter: blur(10px);
        }
        
        .autocomplete-item {
            padding: 0.8rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid rgba(52, 152, 219, 0.2);
            color: var(--btn-text-white);
            transition: var(--btn-transition);
        }
        
        .autocomplete-item:hover {
            background: rgba(52, 152, 219, 0.15);
            color: var(--btn-border-blue);
        }
        
        .autocomplete-item:last-child {
            border-bottom: none;
        }

        /* Boutons primaires - Style uniforme moderne */
        .btn-primary, 
        .btn,
        button[type="submit"],
        .btn-scan,
        .btn-contribute,
        .search-tab,
        .btn-purple {
            background: var(--btn-bg-primary) !important;
            border: 2px solid var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            font-weight: 600 !important;
            padding: var(--btn-padding) !important;
            border-radius: var(--btn-radius) !important;
            font-size: 0.95rem !important;
            transition: var(--btn-transition) !important;
            letter-spacing: 0.3px !important;
            text-decoration: none !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            min-height: 48px !important;
            position: relative !important;
            overflow: hidden !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: var(--btn-shadow) !important;
        }

        /* Effets hover pour tous les boutons */
        .btn-primary:hover, 
        .btn:hover,
        button[type="submit"]:hover,
        .btn-scan:hover,
        .btn-contribute:hover,
        .search-tab:hover,
        .btn-purple:hover {
            background: rgba(25, 25, 25, 0.98) !important;
            border-color: #3498DB !important;
            color: var(--btn-text-white) !important;
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: var(--btn-shadow-hover) !important;
        }

        /* État actif des boutons */
        .btn-primary.active,
        .search-tab.active,
        .btn-filter.active {
            background: rgba(52, 152, 219, 0.15) !important;
            border-color: #3498DB !important;
            color: #3498DB !important;
            box-shadow: inset 0 2px 8px rgba(52, 152, 219, 0.2) !important;
        }

        /* Boutons secondaires */
        .btn-secondary,
        .btn-outline-light,
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-back {
            background: rgba(30, 30, 30, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--btn-text-white) !important;
            font-weight: 600 !important;
            padding: var(--btn-padding) !important;
            border-radius: var(--btn-radius) !important;
            font-size: 0.95rem !important;
            transition: var(--btn-transition) !important;
            backdrop-filter: blur(10px) !important;
            min-height: 48px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .btn-secondary:hover,
        .btn-outline-light:hover,
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-back:hover {
            background: var(--btn-bg-primary) !important;
            border-color: var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--btn-shadow) !important;
        }

        /* Boutons d'action spéciaux */
        .btn-icon {
            width: 48px !important;
            height: 48px !important;
            background: var(--btn-bg-primary) !important;
            border: 1px solid var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: var(--btn-transition) !important;
            backdrop-filter: blur(10px) !important;
        }

        .btn-icon:hover {
            background: rgba(52, 152, 219, 0.2) !important;
            border-color: #3498DB !important;
            transform: translateY(-2px) scale(1.1) !important;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3) !important;
        }

        /* Boutons de filtre */
        .btn-filter {
            background: var(--btn-bg-primary) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: var(--btn-text-white) !important;
            padding: 10px 20px !important;
            transition: var(--btn-transition) !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
        }

        .btn-filter:hover {
            background: rgba(52, 152, 219, 0.1) !important;
            border-color: var(--btn-border-blue) !important;
            color: var(--btn-border-blue) !important;
        }

        /* Boutons de connexion/inscription */
        .btn-connexion {
            background: var(--btn-bg-primary) !important;
            border: 2px solid var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            padding: 12px 24px !important;
            border-radius: var(--btn-radius) !important;
            font-weight: 600 !important;
            transition: var(--btn-transition) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            backdrop-filter: blur(10px) !important;
        }

        .btn-connexion:hover {
            background: rgba(52, 152, 219, 0.15) !important;
            color: var(--btn-border-blue) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--btn-shadow) !important;
        }

        /* Styles pour les boutons outline spéciaux */
        .btn-outline {
            background: transparent !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--btn-text-white) !important;
            font-weight: 600 !important;
            padding: var(--btn-padding) !important;
            border-radius: var(--btn-radius) !important;
            transition: var(--btn-transition) !important;
            backdrop-filter: blur(10px) !important;
            min-height: 48px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .btn-outline:hover {
            background: var(--btn-bg-primary) !important;
            border-color: var(--btn-border-blue) !important;
            color: var(--btn-text-white) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--btn-shadow) !important;
        }

        /* Animations pour tous les boutons */
        @keyframes buttonPulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }

        /* Focus states pour accessibilité */
        .btn-primary:focus,
        .btn:focus,
        button:focus,
        .btn-secondary:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3) !important;
            animation: buttonPulse 1.5s infinite !important;
        }

        /* États disabled */
        .btn-primary:disabled,
        .btn:disabled,
        button:disabled {
            background: rgba(50, 50, 50, 0.5) !important;
            border-color: rgba(100, 100, 100, 0.3) !important;
            color: rgba(255, 255, 255, 0.4) !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-primary, 
            .btn,
            button[type="submit"] {
                padding: 12px 20px !important;
                font-size: 0.9rem !important;
            }
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
                
                <a href="{{ route('social.feed') }}" class="nav-link">
                    <i class="fas fa-stream"></i> Communauté
                </a>
                
                <a href="{{ route('leagues.index') }}" class="nav-link">
                    <i class="fas fa-trophy"></i> Ligues
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
                            <li><a class="dropdown-item" href="{{ route('profile.badges') }}"><i class="fas fa-award me-2"></i> Mes badges</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('leaderboard.global') }}"><i class="fas fa-list-ol me-2"></i> Classement global</a></li>
                            <li><a class="dropdown-item" href="{{ route('leagues.index') }}"><i class="fas fa-trophy me-2"></i> Mes ligues</a></li>
                            <li><a class="dropdown-item" href="{{ route('social.feed') }}"><i class="fas fa-stream me-2"></i> Feed social</a></li>
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
