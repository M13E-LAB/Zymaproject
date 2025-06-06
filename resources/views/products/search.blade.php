@extends('layouts.app')

@section('content')
<div class="zyma-container">
    <!-- Navigation avec barre de recherche intégrée -->
    <nav class="zyma-nav">
        <div class="logo-container">
            <h1 class="zyma-logo">ZYMA</h1>
            <div class="tagline">La communauté des mangeurs malins</div>
        </div>
        <div class="nav-links">
            <a href="{{ route('products.search') }}" class="nav-link">Découvrir</a>
            <a href="{{ route('social.feed') }}" class="nav-link">Communauté</a>
            @if(auth()->check())
                <a href="{{ route('profile.show') }}" class="nav-link">Mon Profil</a>
            @else
                <a href="{{ route('login') }}" class="btn-connexion">Connexion</a>
            @endif
        </div>
    </nav>

    <!-- Section héro avec scanner et comparateur -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Mangez mieux.<br>Dépensez moins.</h1>
            <p class="hero-subtitle">
                Scannez, comparez, échangez, et grimpez dans les ligues du mieux-manger.
            </p>
            
            <div class="search-container">
                <div class="search-tabs">
                    <button class="search-tab active" data-tab="barcode">
                        <i class="fas fa-barcode"></i> Code-barres
                    </button>
                    <button class="search-tab" data-tab="name">
                        <i class="fas fa-shopping-basket"></i> Nom du produit
                    </button>
                    <button class="search-tab" data-tab="camera">
                        <i class="fas fa-camera"></i> Photo
                    </button>
                </div>
                
                <div class="search-box" id="barcode-search">
                    <form action="{{ route('products.fetch') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <input type="text" name="product_code" class="search-input" 
                                   placeholder="Scannez ou entrez un code-barres..." required>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search"></i> Comparer
                            </button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <button id="start-barcode-scanner" class="btn-outline">
                            <i class="fas fa-barcode"></i> Scanner un code-barres
                        </button>
                    </div>
                    <div id="barcode-scanner" style="display:none; margin-top:1rem;"></div>
                </div>

                <div class="search-box hidden" id="name-search">
                    <form action="{{ route('products.searchByName') }}" method="GET">
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" name="query" class="search-input" id="productNameInput"
                                   placeholder="Entrez le nom d'un produit..." required autocomplete="off">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    <div class="name-autocomplete" id="nameAutocomplete"></div>
                </div>
                
                <div class="search-box hidden" id="camera-search">
                    <div class="camera-preview">
                        <video id="camera-preview" autoplay playsinline class="hidden"></video>
                        <div id="camera-placeholder">
                            <i class="fas fa-camera-retro"></i>
                            <p>Prenez une photo de votre produit</p>
                        </div>
                        <canvas id="photo-canvas" class="hidden"></canvas>
                        <img id="captured-image" class="img-fluid hidden" alt="Captured image">
                    </div>
                    <div class="camera-controls">
                        <button id="start-camera" class="btn-outline">
                            <i class="fas fa-camera"></i> Activer l'appareil photo
                        </button>
                        <button id="capture-photo" class="btn-primary hidden">
                            <i class="fas fa-camera"></i> Prendre une photo
                        </button>
                        <button id="retake-photo" class="btn-outline hidden">
                            <i class="fas fa-redo"></i> Reprendre
                        </button>
                        <button id="upload-photo" class="btn-primary hidden">
                            <i class="fas fa-upload"></i> Analyser ce produit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Animations et éléments graphiques -->
        <div class="background-graphics">
            <div class="food-icon fruit" style="top: 15%; left: 10%;">
                <i class="fas fa-apple-alt"></i>
            </div>
            <div class="food-icon vegetable" style="top: 70%; left: 15%;">
                <i class="fas fa-carrot"></i>
            </div>
            <div class="food-icon grain" style="top: 30%; right: 12%;">
                <i class="fas fa-bread-slice"></i>
            </div>
            <div class="food-icon protein" style="top: 60%; right: 8%;">
                <i class="fas fa-egg"></i>
            </div>
            <div class="food-icon dairy" style="top: 40%; left: 85%;">
                <i class="fas fa-cheese"></i>
            </div>
            <div class="price-bubble small" style="top: 25%; right: 25%;">
                <span>-30%</span>
            </div>
            <div class="price-bubble medium" style="top: 65%; left: 25%;">
                <span>-15%</span>
            </div>
            <div class="price-bubble large" style="top: 50%; right: 30%;">
                <span>-50%</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables et resets */
:root {
    --bg-dark: #111111;
    --bg-card: #1a1a1a;
    --bg-light: #222222;
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --text-muted: rgba(255, 255, 255, 0.5);
    --accent-primary: #3498DB;
    --accent-secondary: #4CAF50;
    --accent-tertiary: #3498db;
    --gradient-primary: linear-gradient(135deg, #3498DB, #5DADE2);
    --gradient-secondary: linear-gradient(135deg, #4CAF50, #8BC34A);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.1);
    --shadow-strong: 0 15px 35px rgba(0, 0, 0, 0.2);
    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --font-sans: 'Inter', sans-serif;
}

body, html {
    margin: 0;
    padding: 0;
    font-family: var(--font-sans);
    background-color: var(--bg-dark);
    color: var(--text-primary);
    overflow-x: hidden;
    line-height: 1.6;
}

/* Container principal */
.zyma-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Navigation modernisée */
.zyma-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(15, 15, 15, 0.95) !important;
    border-bottom: 2px solid var(--accent-primary);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15);
}

.logo-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.zyma-logo {
    font-weight: 800;
    font-size: 2.2rem;
    margin: 0;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -1px;
    transition: all 0.3s ease;
}

.zyma-logo:hover {
    transform: scale(1.05);
}

.tagline {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: -0.3rem;
    letter-spacing: 0.05rem;
    font-weight: 400;
}

.nav-links {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.nav-link {
    background: rgba(30, 30, 30, 0.8) !important;
    border: 2px solid rgba(255, 255, 255, 0.2) !important;
    color: var(--text-primary) !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    padding: 12px 24px !important;
    border-radius: 50px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-size: 0.95rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    min-height: 44px !important;
    backdrop-filter: blur(10px) !important;
}

.nav-link:hover {
    background: rgba(15, 15, 15, 0.95) !important;
    border-color: var(--accent-primary) !important;
    color: var(--text-primary) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15) !important;
}

.btn-connexion {
    background: rgba(15, 15, 15, 0.95) !important;
    border: 2px solid var(--accent-primary) !important;
    color: var(--text-primary) !important;
    padding: 12px 24px !important;
    border-radius: 50px !important;
    font-weight: 600 !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    backdrop-filter: blur(10px) !important;
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15) !important;
}

.btn-connexion:hover {
    background: rgba(25, 25, 25, 0.98) !important;
    color: var(--accent-primary) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 32px rgba(52, 152, 219, 0.25) !important;
}

/* Section héro */
.hero-section {
    padding-top: 5rem;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    background-color: var(--bg-dark);
}

.hero-content {
    text-align: center;
    z-index: 10;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.hero-title {
    font-size: 4.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: fadeInUp 1s ease-out;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: var(--text-secondary);
    margin-bottom: 3rem;
    line-height: 1.6;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.search-container {
    background: rgba(40, 40, 40, 0.7);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: var(--shadow-strong);
    backdrop-filter: blur(20px);
    animation: fadeInUp 1s ease-out 0.4s both;
}

.search-tabs {
    display: flex;
    margin-bottom: 1.5rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-md);
    padding: 0.3rem;
}

.search-tab {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    padding: 0.8rem;
    cursor: pointer;
    border-radius: calc(var(--radius-md) - 4px);
    transition: var(--transition-smooth);
    font-weight: 600;
}

.search-tab.active {
    background: rgba(52, 152, 219, 0.2);
    color: var(--accent-primary);
}

.search-tab:hover:not(.active) {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
}

.search-box {
    transition: var(--transition-smooth);
}

.hidden {
    display: none !important;
}

.input-group {
    display: flex;
    align-items: center;
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-md);
    overflow: hidden;
    padding: 0.3rem;
}

.input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 1rem;
    color: var(--accent-primary);
    font-size: 1.2rem;
}

.search-input {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-primary);
    padding: 1rem 0.5rem;
    font-size: 1.1rem;
    outline: none;
}

.search-input::placeholder {
    color: var(--text-muted);
}

.btn-primary {
    background: rgba(15, 15, 15, 0.95) !important;
    border: 2px solid #3498DB !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 14px 28px !important;
    border-radius: 50px !important;
    font-size: 0.95rem !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    letter-spacing: 0.3px !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    min-height: 48px !important;
    backdrop-filter: blur(10px) !important;
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15) !important;
}

.btn-primary:hover {
    background: rgba(25, 25, 25, 0.98) !important;
    border-color: #5DADE2 !important;
    color: #ffffff !important;
    transform: translateY(-3px) scale(1.02) !important;
    box-shadow: 0 8px 32px rgba(52, 152, 219, 0.25) !important;
}

.btn-outline {
    background: transparent !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 14px 28px !important;
    border-radius: 50px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    backdrop-filter: blur(10px) !important;
    min-height: 48px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
}

.btn-outline:hover {
    background: rgba(15, 15, 15, 0.95) !important;
    border-color: #3498DB !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.15) !important;
}

.camera-preview {
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-md);
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    border: 1px dashed rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}

#camera-preview, #captured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: var(--radius-md);
}

#camera-placeholder {
    text-align: center;
    color: var(--text-muted);
}

#camera-placeholder i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.camera-controls {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

/* Autocomplétion pour la recherche par nom */
.name-autocomplete {
    position: absolute;
    top: calc(100% - 1.5rem);
    left: 2rem;
    right: 2rem;
    background: rgba(30, 30, 30, 0.95);
    border-radius: 0 0 var(--radius-md) var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-top: none;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: var(--shadow-strong);
    display: none;
}

.autocomplete-item {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: var(--transition-smooth);
}

.autocomplete-item:hover {
    background: rgba(52, 152, 219, 0.1);
}

.autocomplete-item:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }
    
    .search-tabs {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .search-tab {
        border-radius: var(--radius-sm);
    }
    
    .camera-controls {
        flex-direction: column;
    }
    
    .btn-primary, .btn-outline {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

.background-graphics {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 1;
}

.food-icon {
    position: absolute;
    font-size: 2rem;
    opacity: 0.2;
    animation: float 6s ease-in-out infinite;
}

.fruit {
    color: #FF5722;
    animation-delay: 0s;
}

.vegetable {
    color: #4CAF50;
    animation-delay: 1s;
}

.grain {
    color: #FFC107;
    animation-delay: 2s;
}

.protein {
    color: #9C27B0;
    animation-delay: 3s;
}

.dairy {
    color: #2196F3;
    animation-delay: 4s;
}

.price-bubble {
    position: absolute;
    border-radius: 50%;
    background: rgba(52, 152, 219, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 1px solid rgba(52, 152, 219, 0.4);
    animation: float 8s ease-in-out infinite;
}

.price-bubble.small {
    width: 60px;
    height: 60px;
    font-size: 0.9rem;
    animation-delay: 1s;
}

.price-bubble.medium {
    width: 80px;
    height: 80px;
    font-size: 1.1rem;
    animation-delay: 2s;
}

.price-bubble.large {
    width: 100px;
    height: 100px;
    font-size: 1.3rem;
    animation-delay: 3s;
}

.price-bubble span {
    color: var(--accent-primary);
}
</style>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Changer d'onglet de recherche
    const searchTabs = document.querySelectorAll('.search-tab');
    const searchBoxes = document.querySelectorAll('.search-box');
    
    searchTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Désactiver tous les onglets
            searchTabs.forEach(t => t.classList.remove('active'));
            // Cacher toutes les boîtes de recherche
            searchBoxes.forEach(box => box.classList.add('hidden'));
            
            // Activer l'onglet cliqué
            this.classList.add('active');
            // Afficher la boîte de recherche correspondante
            const tabName = this.getAttribute('data-tab');
            document.getElementById(tabName + '-search').classList.remove('hidden');
        });
    });
    
    // Fonctionnalités de la caméra
    const startCameraBtn = document.getElementById('start-camera');
    const capturePhotoBtn = document.getElementById('capture-photo');
    const retakePhotoBtn = document.getElementById('retake-photo');
    const uploadPhotoBtn = document.getElementById('upload-photo');
    const cameraPreview = document.getElementById('camera-preview');
    const cameraPlaceholder = document.getElementById('camera-placeholder');
    const photoCanvas = document.getElementById('photo-canvas');
    const capturedImage = document.getElementById('captured-image');
    
    if (startCameraBtn) {
        startCameraBtn.addEventListener('click', function() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(stream) {
                        cameraPreview.srcObject = stream;
                        cameraPreview.classList.remove('hidden');
                        cameraPlaceholder.classList.add('hidden');
                        startCameraBtn.classList.add('hidden');
                        capturePhotoBtn.classList.remove('hidden');
                    })
                    .catch(function(error) {
                        alert('Impossible d\'accéder à la caméra: ' + error.message);
                    });
            } else {
                alert('Votre navigateur ne supporte pas l\'accès à la caméra');
            }
        });
    }
    
    if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', function() {
            photoCanvas.width = cameraPreview.videoWidth;
            photoCanvas.height = cameraPreview.videoHeight;
            photoCanvas.getContext('2d').drawImage(cameraPreview, 0, 0, photoCanvas.width, photoCanvas.height);
            
            capturedImage.src = photoCanvas.toDataURL('image/png');
            capturedImage.classList.remove('hidden');
            cameraPreview.classList.add('hidden');
            
            capturePhotoBtn.classList.add('hidden');
            retakePhotoBtn.classList.remove('hidden');
            uploadPhotoBtn.classList.remove('hidden');
            
            // Arrêter la caméra
            cameraPreview.srcObject.getTracks().forEach(track => track.stop());
        });
    }
    
    if (retakePhotoBtn) {
        retakePhotoBtn.addEventListener('click', function() {
            capturedImage.classList.add('hidden');
            retakePhotoBtn.classList.add('hidden');
            uploadPhotoBtn.classList.add('hidden');
            startCameraBtn.classList.remove('hidden');
        });
    }
    
    if (uploadPhotoBtn) {
        uploadPhotoBtn.addEventListener('click', function() {
            alert('Analyse en cours... Cette fonctionnalité est en développement.');
        });
    }
    
    // Autocomplétion pour la recherche par nom
    const productNameInput = document.getElementById('productNameInput');
    const nameAutocomplete = document.getElementById('nameAutocomplete');
    
    if (productNameInput && nameAutocomplete) {
        productNameInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length >= 2) {
                // Requête AJAX pour récupérer les suggestions
                fetch(`/api/products/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        nameAutocomplete.innerHTML = '';
                        
                        if (data.length > 0) {
                            data.forEach(product => {
                                const item = document.createElement('div');
                                item.className = 'autocomplete-item';
                                item.textContent = product.name;
                                
                                item.addEventListener('click', function() {
                                    productNameInput.value = product.name;
                                    nameAutocomplete.style.display = 'none';
                                    document.querySelector('#name-search form').submit();
                                });
                                
                                nameAutocomplete.appendChild(item);
                            });
                            
                            nameAutocomplete.style.display = 'block';
                        } else {
                            nameAutocomplete.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la recherche :', error);
                    });
            } else {
                nameAutocomplete.style.display = 'none';
            }
        });
        
        productNameInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                nameAutocomplete.style.display = 'block';
            }
        });
        
        document.addEventListener('click', function(event) {
            if (!productNameInput.contains(event.target) && !nameAutocomplete.contains(event.target)) {
                nameAutocomplete.style.display = 'none';
            }
        });
    }

    const startBarcodeScannerBtn = document.getElementById('start-barcode-scanner');
    const barcodeScannerDiv = document.getElementById('barcode-scanner');
    let html5QrCode;
    if (startBarcodeScannerBtn) {
        startBarcodeScannerBtn.addEventListener('click', function() {
            startBarcodeScannerBtn.style.display = 'none';
            barcodeScannerDiv.style.display = 'block';
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode('barcode-scanner');
            }
            Html5Qrcode.getCameras().then(cameras => {
                const cameraId = cameras && cameras.length ? cameras[0].id : null;
                html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: 250 },
                    (decodedText, decodedResult) => {
                        console.log('Code détecté:', decodedText);
                        
                        // Valider que c'est bien un code-barres numérique (8, 12, 13 ou 14 chiffres)
                        const cleanCode = decodedText.trim();
                        const isValidBarcode = /^\d{8,14}$/.test(cleanCode);
                        
                        if (!isValidBarcode) {
                            console.log('Code invalide:', cleanCode);
                            // Ne pas arrêter le scanner, continuer à chercher
                            return;
                        }
                        
                        // Arrêter le scanner seulement si on a un code valide
                        html5QrCode.stop();
                        console.log('Code-barres valide détecté:', cleanCode);
                        
                        // Vérifier le token CSRF
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            console.error('Token CSRF non trouvé');
                            return;
                        }
                        
                        console.log('Token CSRF trouvé');
                        
                        // Créer un formulaire et le soumettre vers /fetch
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/fetch';
                        form.style.display = 'none';
                        
                        // Ajouter le token CSRF
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.getAttribute('content');
                        form.appendChild(csrfInput);
                        
                        // Ajouter le code produit
                        const productCodeInput = document.createElement('input');
                        productCodeInput.type = 'hidden';
                        productCodeInput.name = 'product_code';
                        productCodeInput.value = cleanCode;
                        form.appendChild(productCodeInput);
                        
                        console.log('Soumission du formulaire avec code:', cleanCode);
                        
                        // Ajouter au DOM et soumettre
                        document.body.appendChild(form);
                        form.submit();
                    },
                    (errorMessage) => {
                        // Ignorer les erreurs de scan
                    }
                ).catch(err => {
                    alert('Erreur lors de l\'accès à la caméra : ' + err);
                });
            });
        });
    }
});
</script>
@endsection