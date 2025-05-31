@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('leagues.index') }}" class="btn btn-outline-light mb-3">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux ligues
                </a>
                <h1 class="section-title mb-2">📸 Partager mon repas</h1>
                <p class="feed-subtitle">Uploadez une photo de votre repas pour être noté et gagner des points dans vos ligues !</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="card-body p-4">
                        <form action="{{ route('leagues.meal.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Upload d'image -->
                            <div class="mb-4">
                                <label for="image" class="form-label">
                                    <i class="fas fa-camera me-2"></i>Photo de votre repas
                                </label>
                                <div class="upload-area" id="upload-area">
                                    <input type="file" class="form-control d-none" id="image" name="image" accept="image/*" required>
                                    <div class="upload-placeholder" id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt mb-3"></i>
                                        <h5>Cliquez ou glissez votre photo ici</h5>
                                        <p class="text-muted">JPG, PNG, GIF jusqu'à 5MB</p>
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image').click()">
                                            <i class="fas fa-camera me-1"></i> Choisir une photo
                                        </button>
                                    </div>
                                    <div class="upload-preview d-none" id="upload-preview">
                                        <img id="preview-image" src="" alt="Aperçu" class="img-fluid rounded">
                                        <div class="upload-overlay">
                                            <button type="button" class="btn btn-outline-light" onclick="changeImage()">
                                                <i class="fas fa-edit me-1"></i> Changer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Détails du repas -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">
                                            <i class="fas fa-utensils me-1"></i> Nom de votre repas
                                        </label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                               value="{{ old('product_name') }}" placeholder="Ex: Salade de quinoa aux légumes" required>
                                        @error('product_name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="store_name" class="form-label">
                                            <i class="fas fa-map-marker-alt me-1"></i> Lieu du repas
                                        </label>
                                        <input type="text" class="form-control" id="store_name" name="store_name" 
                                               value="{{ old('store_name') }}" placeholder="Ex: Maison, Restaurant..." required>
                                        @error('store_name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Prix et description -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <i class="fas fa-euro-sign me-1"></i> Coût estimé (€)
                                        </label>
                                        <input type="number" step="0.01" min="0" class="form-control" id="price" 
                                               name="price" value="{{ old('price') }}" placeholder="0.00" required>
                                        @error('price')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meal_time" class="form-label">
                                            <i class="fas fa-clock me-1"></i> Moment du repas
                                        </label>
                                        <select class="form-control" id="meal_time" name="meal_time">
                                            <option value="breakfast">🌅 Petit-déjeuner</option>
                                            <option value="lunch" selected>🍽️ Déjeuner</option>
                                            <option value="dinner">🌙 Dîner</option>
                                            <option value="snack">🍎 Collation</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-comment-dots me-1"></i> Description de votre repas
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3" 
                                          placeholder="Décrivez votre repas, les ingrédients utilisés...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Info scoring -->
                            <div class="scoring-info mb-4">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-robot me-2"></i>Comment fonctionne l'analyse IA automatique ?</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>🫀 Santé (50%)</strong><br>
                                            <small>Équilibre nutritionnel, qualité des ingrédients</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>👁️ Visuel (30%)</strong><br>
                                            <small>Présentation, couleurs, appétence</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>🌱 Diversité (20%)</strong><br>
                                            <small>Variété des groupes d'aliments</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="mb-2"><strong>🤖 Analyse automatique : </strong>Notre IA analyse instantanément votre repas !</p>
                                    <p class="mb-0"><strong>Score ≥ 60/100 = Points pour vos ligues ! 🏆</strong></p>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('leagues.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-arrow-left me-2"></i> Annuler
                                </a>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-camera me-2"></i> Partager mon repas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour l'upload de repas */
.upload-area {
    border: 2px dashed #E67E22;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    background: rgba(230, 126, 34, 0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #F39C12;
    background: rgba(230, 126, 34, 0.1);
    transform: translateY(-2px);
}

.upload-area.dragover {
    border-color: #F39C12;
    background: rgba(230, 126, 34, 0.15);
    transform: scale(1.02);
}

.upload-placeholder i {
    font-size: 3rem;
    color: #E67E22;
    margin-bottom: 1rem;
}

.upload-preview {
    position: relative;
    max-width: 100%;
}

.upload-preview img {
    max-height: 300px;
    width: 100%;
    object-fit: cover;
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 12px;
}

.upload-preview:hover .upload-overlay {
    opacity: 1;
}

.scoring-info {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.alert-info {
    background: rgba(0, 123, 255, 0.1) !important;
    border: 1px solid rgba(0, 123, 255, 0.3) !important;
    color: #ffffff !important;
}

/* Responsive */
@media (max-width: 768px) {
    .upload-area {
        padding: 1.5rem 1rem;
        min-height: 150px;
    }
    
    .upload-placeholder i {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const uploadArea = document.getElementById('upload-area');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const uploadPreview = document.getElementById('upload-preview');
    const previewImage = document.getElementById('preview-image');
    
    // Click to upload
    uploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            imageInput.files = files;
            previewFile(files[0]);
        }
    });
    
    // File input change
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            previewFile(this.files[0]);
        }
    });
    
    function previewFile(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploadPlaceholder.classList.add('d-none');
            uploadPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

function changeImage() {
    document.getElementById('upload-placeholder').classList.remove('d-none');
    document.getElementById('upload-preview').classList.add('d-none');
    document.getElementById('image').value = '';
}
</script>
@endsection 