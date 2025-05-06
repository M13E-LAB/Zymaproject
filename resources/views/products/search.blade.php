@extends('layouts.app')

@section('content')
<div class="zyma-container">
    <!-- Section héro avec scanner et comparateur -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Mangez mieux.<br>Dépensez moins.</h1>
            <p class="hero-subtitle">
                L'application qui compare les prix des produits alimentaires<br>
                et vous propose des alternatives plus saines et économiques.
            </p>
            
            <div class="search-container">
                <div class="search-tabs">
                    <button class="search-tab active" data-tab="barcode">
                        <i class="fas fa-barcode"></i> Code-barres
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
            
            <div class="recent-uploads">
                <h4 class="upload-title">Photos récentes de la communauté</h4>
                <div class="recent-photos">
                    <div class="recent-photo-item">
                        <div class="photo-time">il y a 5 min</div>
                    </div>
                    <div class="recent-photo-item">
                        <div class="photo-time">il y a 12 min</div>
                    </div>
                    <div class="recent-photo-item">
                        <div class="photo-time">il y a 23 min</div>
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
    --accent-primary: #E67E22;
    --accent-secondary: #4CAF50;
    --accent-tertiary: #3498db;
    --gradient-primary: linear-gradient(135deg, #E67E22, #F39C12);
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
    background: rgba(230, 126, 34, 0.2);
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
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
}

.btn-outline {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
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

/* Style pour les photos récentes */
.recent-uploads {
    margin-top: 2.5rem;
    text-align: left;
    width: 100%;
    animation: fadeInUp 1s ease-out 0.6s both;
}

.upload-title {
    color: var(--text-secondary);
    font-size: 1rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.recent-photos {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 0.5rem 0;
    scrollbar-width: none;
}

.recent-photos::-webkit-scrollbar {
    display: none;
}

.recent-photo-item {
    min-width: 120px;
    height: 120px;
    border-radius: var(--radius-md);
    background: linear-gradient(45deg, rgba(211, 84, 0, 0.15), rgba(230, 126, 34, 0.15));
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition-smooth);
}

.recent-photo-item:hover {
    transform: translateY(-5px);
    border-color: rgba(230, 126, 34, 0.3);
}

.photo-time {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0.5rem;
    font-size: 0.7rem;
    background: rgba(0, 0, 0, 0.5);
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .camera-controls {
        flex-direction: column;
    }
    
    .recent-photo-item {
        min-width: 100px;
        height: 100px;
    }
}

/* Éléments graphiques d'arrière-plan */
.background-graphics {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
}

.food-icon {
    position: absolute;
    font-size: 2rem;
    opacity: 0.3;
    transition: all 0.5s ease;
    animation: float 6s infinite ease-in-out;
}

.fruit {
    color: #FF9800;
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
    color: #F44336;
    animation-delay: 1.5s;
}

.dairy {
    color: #FFFFFF;
    animation-delay: 0.5s;
}

.price-bubble {
    position: absolute;
    background: var(--gradient-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    opacity: 0.7;
    animation: bubble 8s infinite ease-in-out;
}

.price-bubble.small {
    width: 50px;
    height: 50px;
    font-size: 0.9rem;
    animation-delay: 1s;
}

.price-bubble.medium {
    width: 70px;
    height: 70px;
    font-size: 1.1rem;
    animation-delay: 2s;
}

.price-bubble.large {
    width: 90px;
    height: 90px;
    font-size: 1.4rem;
    animation-delay: 0s;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes bubble {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }
    
    .search-container {
        padding: 1.5rem;
    }
    
    .food-icon, .price-bubble {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets de recherche
    const searchTabs = document.querySelectorAll('.search-tab');
    const searchBoxes = document.querySelectorAll('.search-box');
    
    searchTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Retirer la classe active de tous les onglets
            searchTabs.forEach(t => t.classList.remove('active'));
            
            // Ajouter la classe active à l'onglet cliqué
            this.classList.add('active');
            
            // Masquer toutes les boîtes de recherche
            searchBoxes.forEach(box => box.classList.add('hidden'));
            
            // Afficher la boîte correspondante
            const targetBox = document.getElementById(this.dataset.tab + '-search');
            targetBox.classList.remove('hidden');
        });
    });
    
    // Variables pour les éléments de la caméra
    const startCamera = document.getElementById('start-camera');
    const capturePhoto = document.getElementById('capture-photo');
    const retakePhoto = document.getElementById('retake-photo');
    const uploadPhoto = document.getElementById('upload-photo');
    
    const cameraPreview = document.getElementById('camera-preview');
    const cameraPlaceholder = document.getElementById('camera-placeholder');
    const photoCanvas = document.getElementById('photo-canvas');
    const capturedImage = document.getElementById('captured-image');
    
    let stream = null;
    
    // Démarrer la caméra
    if (startCamera) {
        startCamera.addEventListener('click', function() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(mediaStream) {
                        stream = mediaStream;
                        cameraPreview.srcObject = mediaStream;
                        cameraPreview.classList.remove('hidden');
                        cameraPlaceholder.classList.add('hidden');
                        
                        startCamera.classList.add('hidden');
                        capturePhoto.classList.remove('hidden');
                    })
                    .catch(function(error) {
                        console.error("Impossible d'accéder à la caméra: ", error);
                        alert("Impossible d'accéder à la caméra. Veuillez vérifier les permissions.");
                    });
            } else {
                alert("Votre navigateur ne supporte pas l'accès à la caméra");
            }
        });
    }
    
    // Prendre une photo
    if (capturePhoto) {
        capturePhoto.addEventListener('click', function() {
            const context = photoCanvas.getContext('2d');
            photoCanvas.width = cameraPreview.videoWidth;
            photoCanvas.height = cameraPreview.videoHeight;
            
            context.drawImage(cameraPreview, 0, 0, photoCanvas.width, photoCanvas.height);
            const imageDataUrl = photoCanvas.toDataURL('image/jpeg');
            
            capturedImage.src = imageDataUrl;
            capturedImage.classList.remove('hidden');
            cameraPreview.classList.add('hidden');
            
            capturePhoto.classList.add('hidden');
            retakePhoto.classList.remove('hidden');
            uploadPhoto.classList.remove('hidden');
            
            // Arrêter la caméra après la capture
            stopCamera();
        });
    }
    
    // Reprendre une photo
    if (retakePhoto) {
        retakePhoto.addEventListener('click', function() {
            capturedImage.classList.add('hidden');
            
            // Redémarrer la caméra
            if (startCamera) {
                startCamera.click();
            }
            
            retakePhoto.classList.add('hidden');
            uploadPhoto.classList.add('hidden');
        });
    }
    
    // Analyser la photo
    if (uploadPhoto) {
        uploadPhoto.addEventListener('click', function() {
            // Simuler l'envoi de l'image pour analyse
            alert("Cette fonctionnalité sera bientôt disponible !");
        });
    }
    
    // Fonction pour arrêter la caméra
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => {
                track.stop();
            });
            stream = null;
            cameraPreview.srcObject = null;
        }
    }
});
</script>
@endsection