@extends('layouts.app')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 hero-text">
                <div class="logo-container mb-4">
                    <h1 class="zyma-logo">ZYMA</h1>
                    <div class="etchelast-tag">powered by etchelast</div>
                </div>
                <h2 class="tagline mb-4">
                    THE APP THAT HELPS YOU EAT WELL<br>
                    WITHOUT BREAKING THE BANK
                </h2>
                <p class="rational-quote">" stay rational. "</p>
                <div class="search-box">
                    <form action="{{ route('products.fetch') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input type="text" name="product_code" class="form-control form-control-lg search-input" 
                                   placeholder="Scannez ou entrez un code-barres..." required>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Comparer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 hero-image">
                <div class="npc-character">
                    <svg width="300" height="300" viewBox="0 0 300 300">
                        <circle cx="150" cy="150" r="150" fill="#432C55"/>
                        <circle cx="150" cy="150" r="148" fill="#2B1B3B"/>
                        <circle cx="150" cy="150" r="140" fill="#FF69B4"/>
                        <!-- Simplified NPC face -->
                        <path d="M100,120 Q150,140 200,120" stroke="#FFFFFF" stroke-width="3" fill="none"/>
                        <circle cx="130" cy="100" r="8" fill="#FFFFFF"/>
                        <circle cx="170" cy="100" r="8" fill="#FFFFFF"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="features-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h3>Prix en Temps Réel</h3>
                    <p>Accédez aux prix actualisés de plus de 85 000 produits dans toute la France.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Comparaison Locale</h3>
                    <p>Trouvez les meilleurs prix dans les magasins près de chez vous.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Historique des Prix</h3>
                    <p>Suivez l'évolution des prix et choisissez le meilleur moment pour acheter.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');

:root {
    --bg-primary: #000000;
    --bg-secondary: #121212;
    --bg-tertiary: #1E1E1E;
    --accent-primary: #00D1B2;
    --accent-gradient: linear-gradient(45deg, #00D1B2, #00F2C3);
    --accent-secondary: #666666;
    --text-primary: #FFFFFF;
    --text-secondary: #A0A0A0;
}

.hero-section {
    background-color: var(--bg-primary);
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 50%, rgba(0, 209, 178, 0.1) 0%, rgba(0, 0, 0, 0) 50%);
    pointer-events: none;
}

.logo-container {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
    margin-bottom: 1rem;
}

.zyma-logo {
    font-family: 'Inter', sans-serif;
    font-size: 7rem;
    font-weight: 800;
    text-align: center;
    margin: 0;
    position: relative;
    z-index: 2;
    letter-spacing: -3px;
    text-transform: uppercase;
    color: var(--text-primary);
    text-shadow: 0 0 30px rgba(0, 209, 178, 0.3);
}

.etchelast-tag {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    font-weight: 400;
    color: var(--accent-secondary);
    opacity: 0.8;
    letter-spacing: 2px;
    margin-top: 0.5rem;
}

.rational-quote {
    font-family: 'Inter', sans-serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-align: center;
    margin: 2rem 0;
    font-style: italic;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.tagline {
    font-family: 'Inter', sans-serif;
    font-size: 2.2rem;
    color: var(--text-primary);
    text-align: center;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 2rem;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.search-box {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(10px);
    padding: 2rem;
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.input-group-text {
    background: transparent;
    border: none;
    color: var(--accent-primary);
}

.search-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: none !important;
    color: var(--text-primary) !important;
    padding: 1.5rem 1rem;
    font-size: 1.1rem;
    border-radius: 12px !important;
}

.search-input::placeholder {
    color: var(--text-secondary);
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

.contact-info {
    color: #FFFFFF;
    font-weight: 500;
    letter-spacing: 1px;
}

.hero-image img {
    max-width: 100%;
    height: auto;
}

.features-section {
    background-color: var(--bg-secondary);
    padding: 5rem 0;
}

.feature-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(10px);
    padding: 2.5rem;
    border-radius: 24px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.feature-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent-primary);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--accent-gradient);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: rotate(10deg);
}

.feature-icon i {
    font-size: 2rem;
    color: var(--bg-primary);
    transform: rotate(-10deg);
}

.npc-character {
    position: relative;
    width: 300px;
    height: 300px;
    margin: 0 auto;
    animation: float 6s ease-in-out infinite;
}

.npc-character svg {
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 3rem 0;
    }
    .tagline {
        font-size: 1.5rem;
    }
    .hero-image {
        margin-top: 3rem;
    }
}

.question-mark-placeholder {
    position: relative;
    width: 100%;
    height: 400px;
    background: #151515;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.search-illustration {
    position: relative;
    width: 200px;
    height: 200px;
}

.search-circle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    background: #FFFFFF;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-circle i {
    font-size: 3rem;
    color: #0A0A0A;
}

.price-tag {
    position: absolute;
    top: 20%;
    right: 0;
    background: #E0E0E0;
    padding: 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 3s ease-in-out infinite;
}

.price-tag i, .price-tag span {
    font-size: 1.5rem;
    color: #0A0A0A;
}

.shopping-cart {
    position: absolute;
    bottom: 10%;
    left: 10%;
    background: #CCCCCC;
    padding: 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 3s ease-in-out infinite;
    animation-delay: 1s;
}

.shopping-cart i {
    font-size: 1.5rem;
    color: #0A0A0A;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

/* Add modern social proof section */
.social-proof {
    position: absolute;
    bottom: 2rem;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 2rem;
    color: var(--text-secondary);
}

.social-proof-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.social-proof-item i {
    color: var(--accent-primary);
}

/* Add floating elements animation */
.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
}

.floating-element {
    position: absolute;
    background: var(--accent-gradient);
    border-radius: 50%;
    opacity: 0.1;
    animation: float 20s infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0); }
    25% { transform: translate(100px, 100px); }
    50% { transform: translate(0, 200px); }
    75% { transform: translate(-100px, 100px); }
}
</style>
@endsection