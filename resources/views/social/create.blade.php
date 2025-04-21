@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 mb-0">Partager un produit</h1>
            <p class="text-secondary">Partagez vos trouvailles dans les supermarchés</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('social.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="post_type" class="form-label">Type de publication</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_price" value="price" checked>
                                    <label class="form-check-label" for="type_price">
                                        <i class="fas fa-tag me-1"></i> Prix
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_deal" value="deal">
                                    <label class="form-check-label" for="type_deal">
                                        <i class="fas fa-percent me-1"></i> Promo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_meal" value="meal">
                                    <label class="form-check-label" for="type_meal">
                                        <i class="fas fa-utensils me-1"></i> Repas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_review" value="review">
                                    <label class="form-check-label" for="type_review">
                                        <i class="fas fa-star me-1"></i> Avis
                                    </label>
                                </div>
                            </div>
                            @error('post_type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Nom du produit</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                    @error('product_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_name" class="form-label">Magasin</label>
                                    <input type="text" class="form-control" id="store_name" name="store_name" value="{{ old('store_name') }}" required>
                                    @error('store_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Prix actuel (€)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="regular_price" class="form-label">Prix habituel (€) <small class="text-secondary">(optionnel)</small></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price') }}">
                                    @error('regular_price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Description <small class="text-secondary">(optionnel)</small></label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Photo du produit</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <div class="form-text">Prenez une photo du produit dans son contexte, comme BeReal !</div>
                            @error('image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="use_location" name="use_location">
                                <label class="form-check-label" for="use_location">
                                    Utiliser ma position actuelle
                                </label>
                            </div>
                            <div id="location_fields" class="d-none mt-3">
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Adresse approximative..." readonly>
                            </div>
                        </div>
                        
                        <div id="expires_field" class="mb-4 d-none">
                            <label for="expires_at" class="form-label">Date de fin de l'offre</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('social.feed') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i> Retour
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-share-alt me-2"></i> Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Afficher le champ de date d'expiration uniquement pour les promos
        const typeRadios = document.querySelectorAll('input[name="post_type"]');
        const expiresField = document.getElementById('expires_field');
        
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'deal') {
                    expiresField.classList.remove('d-none');
                } else {
                    expiresField.classList.add('d-none');
                }
            });
        });
        
        // Géolocalisation
        const useLocationCheckbox = document.getElementById('use_location');
        const locationFields = document.getElementById('location_fields');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const addressInput = document.getElementById('address');
        
        useLocationCheckbox.addEventListener('change', function() {
            if (this.checked) {
                locationFields.classList.remove('d-none');
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                        
                        // Reverse geocoding (simplifié)
                        addressInput.value = "Position détectée";
                    }, function(error) {
                        alert("Impossible d'obtenir votre position. " + error.message);
                        useLocationCheckbox.checked = false;
                        locationFields.classList.add('d-none');
                    });
                } else {
                    alert("La géolocalisation n'est pas supportée par votre navigateur.");
                    useLocationCheckbox.checked = false;
                    locationFields.classList.add('d-none');
                }
            } else {
                locationFields.classList.add('d-none');
                latitudeInput.value = '';
                longitudeInput.value = '';
                addressInput.value = '';
            }
        });
        
        // Aperçu de l'image
        const imageInput = document.getElementById('image');
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Vous pourriez ajouter un aperçu de l'image ici si souhaité
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection 