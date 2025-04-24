@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body p-4 text-center">
                    <div class="display-1 mb-3 text-primary">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h1 class="display-5 mb-3">Bienvenue sur ZYMA !</h1>
                    <p class="lead text-secondary mb-4">Personnalisez votre expérience en quelques étapes simples et commencez à gagner des points dès maintenant.</p>
                    
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" id="onboardingProgress"></div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mb-2">
                        <span class="badge rounded-pill bg-primary px-3 py-2" id="step1Badge">
                            <i class="fas fa-user-circle me-1"></i> Profil
                        </span>
                        <span class="badge rounded-pill bg-secondary px-3 py-2" id="step2Badge">
                            <i class="fas fa-camera me-1"></i> Avatar
                        </span>
                        <span class="badge rounded-pill bg-secondary px-3 py-2" id="step3Badge">
                            <i class="fas fa-heart me-1"></i> Préférences
                        </span>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data" id="onboardingForm">
                @csrf
                
                <!-- Étape 1: Profil -->
                <div class="card mb-4" id="step1">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Votre profil</h3>
                        
                        <div class="mb-4">
                            <label for="bio" class="form-label">Présentez-vous en quelques mots</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Parlez-nous de vous, de vos habitudes alimentaires, de vos supermarchés préférés...">{{ old('bio') }}</textarea>
                            <div class="form-text">Cette description apparaîtra sur votre profil public.</div>
                            @error('bio')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-light" disabled>
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            
                            <button type="button" class="btn btn-primary" id="step1Next">
                                Suivant <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Étape 2: Avatar -->
                <div class="card mb-4 d-none" id="step2">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Choisissez votre avatar</h3>
                        
                        <div class="mb-4 text-center">
                            <div class="avatar-preview mx-auto mb-3" style="width: 150px; height: 150px; border-radius: 50%; background-color: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <i class="fas fa-user-circle" style="font-size: 6rem; color: var(--text-secondary);" id="defaultAvatar"></i>
                                <img src="" alt="Avatar Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;" id="avatarPreview">
                            </div>
                            
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Téléchargez une photo de profil</label>
                                <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                            </div>
                            <div class="form-text">Une image carrée de 300x300 pixels est recommandée.</div>
                            @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-light" id="step2Prev">
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            
                            <button type="button" class="btn btn-primary" id="step2Next">
                                Suivant <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Étape 3: Préférences -->
                <div class="card mb-4 d-none" id="step3">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Vos préférences alimentaires</h3>
                        
                        <div class="mb-4">
                            <p class="text-secondary mb-3">Sélectionnez les catégories qui vous intéressent pour personnaliser votre feed :</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="bio" id="cat_bio">
                                        <label class="form-check-label" for="cat_bio">
                                            <i class="fas fa-leaf text-success me-2"></i> Produits Bio
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="vegan" id="cat_vegan">
                                        <label class="form-check-label" for="cat_vegan">
                                            <i class="fas fa-seedling text-success me-2"></i> Vegan
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="glutenfree" id="cat_glutenfree">
                                        <label class="form-check-label" for="cat_glutenfree">
                                            <i class="fas fa-bread-slice text-warning me-2"></i> Sans Gluten
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="local" id="cat_local">
                                        <label class="form-check-label" for="cat_local">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i> Produits Locaux
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="deals" id="cat_deals">
                                        <label class="form-check-label" for="cat_deals">
                                            <i class="fas fa-percent text-primary me-2"></i> Bonnes Affaires
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="ready" id="cat_ready">
                                        <label class="form-check-label" for="cat_ready">
                                            <i class="fas fa-utensils text-info me-2"></i> Plats Préparés
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="exotic" id="cat_exotic">
                                        <label class="form-check-label" for="cat_exotic">
                                            <i class="fas fa-pepper-hot text-danger me-2"></i> Produits Exotiques
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="favorite_categories[]" value="desserts" id="cat_desserts">
                                        <label class="form-check-label" for="cat_desserts">
                                            <i class="fas fa-cookie text-warning me-2"></i> Desserts
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            @error('favorite_categories')
                                <div class="text-danger mt-1">Veuillez sélectionner au moins une catégorie</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-light" id="step3Prev">
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Terminer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="card bg-dark border-0">
                <div class="card-body p-4 text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="badge-item me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-award" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="mb-1">Gagnez 100 points !</h4>
                            <p class="mb-0 text-secondary">En complétant votre profil</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.getElementById('onboardingProgress');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const step1Badge = document.getElementById('step1Badge');
        const step2Badge = document.getElementById('step2Badge');
        const step3Badge = document.getElementById('step3Badge');
        
        const step1Next = document.getElementById('step1Next');
        const step2Next = document.getElementById('step2Next');
        const step2Prev = document.getElementById('step2Prev');
        const step3Prev = document.getElementById('step3Prev');
        
        // Avatar preview
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const defaultAvatar = document.getElementById('defaultAvatar');
        
        // Navigation
        step1Next.addEventListener('click', function() {
            step1.classList.add('d-none');
            step2.classList.remove('d-none');
            progressBar.style.width = '33%';
            step1Badge.classList.remove('bg-primary');
            step1Badge.classList.add('bg-success');
            step2Badge.classList.remove('bg-secondary');
            step2Badge.classList.add('bg-primary');
        });
        
        step2Prev.addEventListener('click', function() {
            step2.classList.add('d-none');
            step1.classList.remove('d-none');
            progressBar.style.width = '0%';
            step2Badge.classList.remove('bg-primary');
            step2Badge.classList.add('bg-secondary');
            step1Badge.classList.remove('bg-success');
            step1Badge.classList.add('bg-primary');
        });
        
        step2Next.addEventListener('click', function() {
            step2.classList.add('d-none');
            step3.classList.remove('d-none');
            progressBar.style.width = '66%';
            step2Badge.classList.remove('bg-primary');
            step2Badge.classList.add('bg-success');
            step3Badge.classList.remove('bg-secondary');
            step3Badge.classList.add('bg-primary');
        });
        
        step3Prev.addEventListener('click', function() {
            step3.classList.add('d-none');
            step2.classList.remove('d-none');
            progressBar.style.width = '33%';
            step3Badge.classList.remove('bg-primary');
            step3Badge.classList.add('bg-secondary');
            step2Badge.classList.remove('bg-success');
            step2Badge.classList.add('bg-primary');
        });
        
        // Avatar preview
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.style.display = 'block';
                    defaultAvatar.style.display = 'none';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Animation d'entrée
        document.querySelectorAll('.card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * (index + 1));
        });
        
        // Animation de la barre de progression au chargement
        setTimeout(() => {
            progressBar.style.transition = 'width 0.5s ease-in-out';
            progressBar.style.width = '0%';
        }, 300);
        
        // Valider le formulaire à la soumission
        document.getElementById('onboardingForm').addEventListener('submit', function(e) {
            // Vérifier qu'au moins une catégorie est sélectionnée
            const checkboxes = document.querySelectorAll('input[name="favorite_categories[]"]:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                const error = document.createElement('div');
                error.className = 'text-danger mt-1';
                error.textContent = 'Veuillez sélectionner au moins une catégorie';
                
                // Vérifier si l'erreur existe déjà
                const existingError = document.querySelector('#step3 .text-danger');
                if (!existingError) {
                    document.querySelector('.row').after(error);
                }
            }
        });
    });
</script>
@endsection 