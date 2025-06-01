@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 mb-0">Modifier la publication</h1>
            <p class="text-secondary">Modifiez les informations de votre publication</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('social.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="post_type" class="form-label">Type de publication</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_price" value="price" {{ $post->post_type === 'price' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_price">
                                        <i class="fas fa-tag me-1"></i> Prix
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_deal" value="deal" {{ $post->post_type === 'deal' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_deal">
                                        <i class="fas fa-percent me-1"></i> Promo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_meal" value="meal" {{ $post->post_type === 'meal' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_meal">
                                        <i class="fas fa-utensils me-1"></i> Repas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_type" id="type_review" value="review" {{ $post->post_type === 'review' ? 'checked' : '' }}>
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
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $post->product_name) }}" required>
                                    @error('product_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_name" class="form-label">Magasin</label>
                                    <input type="text" class="form-control" id="store_name" name="store_name" value="{{ old('store_name', $post->store_name) }}" required>
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
                                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $post->price) }}" required>
                                    @error('price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="regular_price" class="form-label">Prix habituel (€) <small class="text-secondary">(optionnel)</small></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price', $post->regular_price) }}">
                                    @error('regular_price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Description <small class="text-secondary">(optionnel)</small></label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $post->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image actuelle -->
                        @if($post->image)
                            <div class="mb-3">
                                <label class="form-label">Image actuelle</label>
                                <div class="current-image mb-3">
                                    <img src="{{ $post->image }}" alt="Image actuelle" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Nouvelle photo (optionnel)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Laissez vide pour conserver l'image actuelle</div>
                            @error('image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="expires_field" class="mb-4 {{ $post->post_type === 'deal' ? '' : 'd-none' }}">
                            <label for="expires_at" class="form-label">Date de fin de l'offre</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at" value="{{ old('expires_at', $post->expires_at ? $post->expires_at->format('Y-m-d') : '') }}">
                            @error('expires_at')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('social.show', $post) }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i> Retour
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Enregistrer les modifications
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
    });
</script>
@endsection 